<?php

declare(strict_types=1);

namespace App\Clients\Middleware\RateLimitingMiddleware\CountRequestLimiter;

class GoogleSearchRateLimiterMiddleware extends AbstractCountRequestRateLimiterMiddleware
{
    protected function getKey(): string
    {
        return hash('sha256', config('google.api_key'));
    }
}
