<?php

declare(strict_types=1);

namespace App\Clients\Middleware\RateLimitingMiddleware;

use App\Clients\Middleware\RateLimitingMiddleware\HeaderRateLimiter\HeaderRateLimiterInterface;
use App\Clients\Middleware\RateLimitingMiddleware\HeaderRateLimiter\RateLimiterServiceInterface;
use App\Clients\Middleware\RateLimitingMiddleware\HeaderRateLimiter\TrackRequestRateLimiterInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

abstract class AbstractMiddleware
{
    public function __construct(private readonly RateLimiterServiceInterface $rateLimiter)
    {
    }

    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            if (!$this->rateLimiter->canMakeRequest()) {
                throw new RuntimeException('Rate limit exceeded for ' . $request->getUri()->getHost());
            }

            if ($this->rateLimiter instanceof TrackRequestRateLimiterInterface) {
                $this->rateLimiter->trackRequest();
            }

            return $handler($request, $options)->then(
                function (ResponseInterface $response) {
                    if ($this->rateLimiter instanceof HeaderRateLimiterInterface) {
                        $this->rateLimiter->updateRateLimits($response);
                    }
                    return $response;
                }
            );
        };
    }
}
