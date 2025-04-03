<?php

namespace App\Helpers\RateLimitingMiddleware\HeaderRateLimiter;

use DateTimeInterface;
use Psr\Http\Message\ResponseInterface;

interface HeaderRateLimiterInterface extends RateLimiterServiceInterface
{
    public function updateRateLimits(ResponseInterface $response): void;

    public function setRemainingCalls(int $limit, DateTimeInterface $expirationDate): void;
}
