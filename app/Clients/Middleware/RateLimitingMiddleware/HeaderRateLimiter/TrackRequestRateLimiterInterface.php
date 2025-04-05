<?php

namespace App\Clients\Middleware\RateLimitingMiddleware\HeaderRateLimiter;

interface TrackRequestRateLimiterInterface extends RateLimiterServiceInterface
{
    public function trackRequest();
}
