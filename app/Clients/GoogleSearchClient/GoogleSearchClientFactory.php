<?php

declare(strict_types=1);

namespace App\Clients\GoogleSearchClient;

use App\Clients\ClientInterface;
use App\Clients\GuzzleClient;
use App\Helpers\RateLimitingMiddleware\CountRequestLimiter\CountRequestRateLimiter;
use App\Helpers\RateLimitingMiddleware\CountRequestLimiter\GoogleSearchRateLimiterMiddleware;
use App\Helpers\RateLimitingMiddleware\GoogleSearchHeaderRateLimiterMiddleware;
use App\Helpers\RateLimitingMiddleware\RateLimitingMiddleWare;
use App\Helpers\RateLimitingMiddleware\TimeUnit;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;

class GoogleSearchClientFactory
{
    private const BASE_URL = 'https://www.googleapis.com';

    public static function make(): ClientInterface
    {
        $client = new Client(self::settings());

        return new GuzzleClient($client);
    }

    private static function settings(): array
    {
        return [
            'base_uri' => self::BASE_URL,
            'headers'  => self::getHeaders(),
            'handler'  => self::getStack(),
            'debug'   => true,
        ];
    }

    private static function getHeaders(): array
    {
        return [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    private static function getStack(): HandlerStack
    {
        $stack = HandlerStack::create();
        $handler = new CurlHandler();
        $stack->setHandler($handler);

        $stack->push(Middleware::mapRequest(function (Request $request) {
            $uri = $request->getUri();
            $uri = $uri->withQuery(self::getDefaultQueryParams($request));
            return $request->withUri($uri);
        }));

        $rateLimiter = app()->makeWith(CountRequestRateLimiter::class, [
                            'limit' => 30,
                            'key' => config('google.api_key'),
                            'unit' => TimeUnit::MINUTE,
                        ]);
        $middleware = app()->makeWith(GoogleSearchRateLimiterMiddleware::class, ['rateLimiter' => $rateLimiter]);
        $stack->push($middleware);

        return $stack;
    }

    private static function getDefaultQueryParams(Request $request): string
    {
        parse_str($request->getUri()->getQuery(), $existingQueryParams);

        $params = [
            'key' => config('google.api_key'),
            'cx'  => config('google.cse_id'),
        ];

        $params = array_merge($params, $existingQueryParams);

        return http_build_query($params, '', '&');
    }
}
