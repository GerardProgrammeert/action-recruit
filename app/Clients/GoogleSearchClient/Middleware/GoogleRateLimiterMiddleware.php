<?php

namespace App\Clients\GoogleSearchClient\Middleware;

use App\Clients\Middleware\RateLimitingMiddleware\HeaderRateLimiter\TrackRequestRateLimiterInterface;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

final readonly class GoogleRateLimiterMiddleware implements TrackRequestRateLimiterInterface
{
    public function __construct(
        private string $cacheKey,
        private int $maxRateLimit = 10
    ) {
        $this->initRateLimiter();
    }

    private function initRateLimiter(): void
    {
        if (!Cache::has($this->cacheKey)) {
            Cache::put($this->cacheKey, $this->maxRateLimit, now()->addMinute());
        }
    }

    public function canMakeRequest(): bool
    {
        return Cache::get($this->cacheKey) > 0;
    }

    public function trackRequest(): void
    {
        $remainingRequests = Cache::get($this->cacheKey);

        if ($remainingRequests > 0) {
            Cache::decrement($this->cacheKey);
        } else {
            throw new RuntimeException('Rate limit exceeded for this minute.');
        }
    }
}
