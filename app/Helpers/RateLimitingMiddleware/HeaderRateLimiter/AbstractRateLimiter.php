<?php

declare(strict_types=1);

namespace App\Helpers\RateLimitingMiddleware\HeaderRateLimiter;

use App\Helpers\RateLimitingMiddleware\TimeUnit;
use Carbon\Carbon;

class AbstractRateLimiter
{
    //todo remove
    protected function getExpiration(TimeUnit $unit): \DateTimeInterface
    {
        $now = Carbon::now('UTC');
        $expirationTime = match ($unit) {
            TimeUnit::DAY => $now->setTime(12, 0),
            TimeUnit::HOUR => $now->addHour()->startOfHour(),
            TimeUnit::MINUTE => $now->addMinute()->startOfMinute(),
        };

        return $expirationTime;
    }
}
