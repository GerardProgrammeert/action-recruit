<?php

namespace App\Clients\Middleware\RateLimitingMiddleware\HeaderRateLimiter;

interface RateLimiterServiceInterface
{
    public function canMakeRequest(): bool;
}
