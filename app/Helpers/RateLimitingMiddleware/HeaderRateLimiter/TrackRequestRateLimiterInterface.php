<?php

namespace App\Helpers\RateLimitingMiddleware\HeaderRateLimiter;

interface TrackRequestRateLimiterInterface extends RateLimiterServiceInterface
{
    public function trackRequest();
}
