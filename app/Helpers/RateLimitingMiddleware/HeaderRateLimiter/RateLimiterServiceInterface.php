<?php

namespace App\Helpers\RateLimitingMiddleware\HeaderRateLimiter;

use DateTimeInterface;

interface RateLimiterServiceInterface
{
    public function canMakeRequest(): bool;
}
