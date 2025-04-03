<?php

declare(strict_types=1);

namespace App\Helpers\RateLimitingMiddleware\CountRequestLimiter;

use App\Helpers\RateLimitingMiddleware\TimeUnit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class CountRequestRateLimiter
{
    public function __construct(private int $limit, private string $key, private TimeUnit $unit = TimeUnit::DAY)
    {
    }

    public function canMakeRequest(): bool
    {
        if (!Cache::has($this->key)) {
            $this->setLimit();
        }

        if (Cache::get($this->key) > 0) {
            return true;
        }

        return false;
    }

    public function count(): void
    {
        if (!Cache::has($this->key)) {
            $this->setLimit();
        }

        Cache::decrement($this->key);
    }

    private function setLimit(): void
    {
        Cache::put($this->key, $this->limit, $this->getExpiration());
    }

    private function getExpiration(): \DateTimeInterface
    {
        $now = Carbon::now('UTC');
        $expirationTime = match ($this->unit) {
            TimeUnit::DAY => $now->setTime(12, 0),
            TimeUnit::HOUR => $now->addHour()->startOfHour(),
            TimeUnit::MINUTE => $now->addMinute()->startOfMinute(),
        };

        return $expirationTime;
    }
}
