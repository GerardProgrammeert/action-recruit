<?php

declare(strict_types=1);

namespace App\Clients\GoogleSearchClient;

use App\Clients\ClientFactoryInterface;
use App\Clients\ClientInterface;
use App\Clients\GuzzleClient;
use App\Clients\Middleware\RateLimitingMiddleware\CountRequestLimiter\CountRequestRateLimiter;
use App\Clients\Middleware\RateLimitingMiddleware\CountRequestLimiter\GoogleSearchRateLimiterMiddleware;
use App\Clients\Middleware\RateLimitingMiddleware\TimeUnit;
use App\Clients\Middleware\ResponseLoggerMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;

final class GoogleSearchClientFactory extends AbstractClientFactory implements ClientFactoryInterface
{
    public function getStack(): HandlerStack
    {
        $stack = HandlerStack::create();
        $handler = new CurlHandler();
        $stack->setHandler($handler);

        $stack->push(Middleware::mapRequest(function (Request $request) {
            $uri = $request->getUri();
            $uri = $uri->withQuery(self::getDefaultQueryParams($request));
            return $request->withUri($uri);
        }));

        //@todo controller counter
        $rateLimiter = app()->makeWith(CountRequestRateLimiter::class, [
                            'limit' => 30,
                            'key' => $this->apiKey,
                            'unit' => TimeUnit::MINUTE,
                        ]);
        $middleware = app()->makeWith(GoogleSearchRateLimiterMiddleware::class, ['rateLimiter' => $rateLimiter]);
        $stack->push($middleware);

        $responseLoggerMiddleware = new ResponseLoggerMiddleware();
        $stack->push($responseLoggerMiddleware);

        return $stack;
    }
}
