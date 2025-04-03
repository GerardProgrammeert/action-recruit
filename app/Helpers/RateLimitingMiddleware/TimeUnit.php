<?php

namespace App\Helpers\RateLimitingMiddleware;

enum TimeUnit: string
{
    case DAY = 'day';
    case HOUR = 'hour';
    case MINUTE = 'minute';
}
