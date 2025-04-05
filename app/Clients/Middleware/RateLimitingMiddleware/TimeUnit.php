<?php

namespace App\Clients\Middleware\RateLimitingMiddleware;

enum TimeUnit: string
{
    case DAY = 'day';
    case HOUR = 'hour';
    case MINUTE = 'minute';
}
