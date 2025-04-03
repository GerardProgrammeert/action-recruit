<?php

declare(strict_types=1);

namespace App\Helpers\RateLimitingMiddleware\HeaderRateLimiter;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Facades\Cache;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class GitHubRateLimiter extends AbstractRateLimiter implements HeaderRateLimiterInterface
{
    public function __construct(
        private readonly string $key,
        private readonly int $maxRateLimit = 150,
    ) {
        $this->initRateLimiter();
    }

    private function initRateLimiter(): void
    {
        if(!Cache::has($this->key)) {
            Cache::put($this->key, $this->maxRateLimit);
        }
    }

    public function canMakeRequest(): bool
    {
        return Cache::get($this->key) > 0;
    }

    public function updateRateLimits(ResponseInterface $response): void
    {
        $headers = $response->getHeaders();

        if (!isset($headers['X-RateLimit-Remaining'], $headers['X-RateLimit-Reset'])) {
            throw new RuntimeException('GitHub rate limit headers missing');
        }

        $remainingCalls = (int) $headers['X-RateLimit-Remaining'][0];
        $expiration = Carbon::createFromTimestamp((int) $headers['X-RateLimit-Reset'][0], 'UTC');

        $this->setRemainingCalls($remainingCalls, $expiration);
    }

    public function setRemainingCalls(int $limit, DateTimeInterface $expirationDate): void
    {
        Cache::put($this->key, $limit, $expirationDate);
    }
}
