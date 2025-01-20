<?php

declare(strict_types=1);

namespace App\Helpers\RateLimitingMiddleware\HeaderRateLimiter;

use Carbon\Carbon;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

abstract class AbstractHeaderRateLimiterMiddleware
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
            return $handler($request, $options)->then(
                function (ResponseInterface $response) {
                    $this->setRateLimitFromHeaders($response->getHeaders());
                    return $response;
                }
            );
        };
    }

    private function setRateLimitFromHeaders(array $headers): void
    {
        $remainingRequest = $headers['X-RateLimit-Remaining'][0] ?? null;
        $resetTimestamp = $headers['X-RateLimit-Reset'][0] ?? null;

        if (!$remainingRequest && !$resetTimestamp) {
            throw new RunTimeException('Cannot set remaining rate limit');
        }

        $expirationDate = Carbon::createFromTimestamp($resetTimestamp, 'UTC');
        $this->rateLimiter->setRemainingCalls((int) $remainingRequest, $expirationDate);
    }
}
