<?php

declare(strict_types=1);

namespace App\Helpers\RateLimitingMiddleware\HeaderRateLimiter;

use DateTimeInterface;
use Illuminate\Support\Facades\Cache;

class RateLimiterService implements RateLimiterServiceInterface
{
    public function __construct(private string $key)
    {
    }

    public function canMakeRequest(): bool
    {
        return !Cache::has($this->key) || Cache::get($this->key) > 0;
    }

    public function setRemainingCalls(int $limit, DateTimeInterface $expirationDate): void
    {
        Cache::put($this->key, $limit, $expirationDate);
    }
}
