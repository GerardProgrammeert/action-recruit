<?php

declare(strict_types=1);

namespace App\Clients\GitHubClient\Middleware;

use App\Clients\Middleware\RateLimitingMiddleware\HeaderRateLimiter\HeaderRateLimiterInterface;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Facades\Cache;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class GitHubRateLimiterMiddleware implements HeaderRateLimiterInterface
{
    /**
     * @see https://docs.github.com/en/rest/search/search?apiVersion=2022-11-28
     */
    public function __construct(
        protected readonly string $cacheKey,
        private readonly int $maxRateLimit = 10,
    ) {
        $this->initRateLimiter();
    }

    private function initRateLimiter(): void
    {
        if (!Cache::has($this->cacheKey)) {
            Cache::put($this->cacheKey, $this->maxRateLimit);
        }
    }

    public function canMakeRequest(): bool
    {
        return Cache::get($this->cacheKey) > 0;
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
        Cache::put($this->cacheKey, $limit, $expirationDate);
    }
}
