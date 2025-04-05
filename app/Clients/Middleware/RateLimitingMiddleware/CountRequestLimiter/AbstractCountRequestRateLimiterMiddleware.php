<?php

declare(strict_types=1);

namespace App\Clients\Middleware\RateLimitingMiddleware\CountRequestLimiter;

use Psr\Http\Message\RequestInterface;
use RuntimeException;

abstract class AbstractCountRequestRateLimiterMiddleware
{
    public function __construct(private CountRequestRateLimiter $rateLimiter)
    {
    }

    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            if (!$this->rateLimiter->canMakeRequest()) {
                throw new RuntimeException('Rate limit exceeded for ' . $request->getUri()->getHost());
            }
            $this->requestCounter();

            return $handler($request, $options);
        };
    }

    private function requestCounter(): void
    {
        $this->rateLimiter->count();
    }

    abstract protected function getKey(): string;
}
