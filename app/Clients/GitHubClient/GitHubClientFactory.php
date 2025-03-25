<?php

declare(strict_types=1);

namespace App\Clients\GitHubClient;

use App\Clients\ClientInterface;
use App\Clients\GuzzleClient;
use App\Helpers\RateLimitingMiddleware\HeaderRateLimiter\GitHubHeaderRateLimiterMiddleware;
use App\Helpers\RateLimitingMiddleware\HeaderRateLimiter\RateLimiterServiceInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;

class GitHubClientFactory
{
    private const BASE_URL = 'https://api.github.com/';

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
        ];
    }

    private static function getHeaders(): array
    {
        return [
            'Authorization' => 'token ' . config('github.api_key'),
            'Accept'        => 'application/vnd.github.v3+json',
        ];
    }

    private static function getStack(): HandlerStack
    {
        $stack = HandlerStack::create();

        $handler = new CurlHandler();
        $stack->setHandler($handler);

        $rateLimiter = app()->makeWith(RateLimiterServiceInterface::class, [
                            'key' => config('github.api_key')
                        ]);

        $middleware = app()->makeWith(GitHubHeaderRateLimiterMiddleware::class, ['rateLimiter' => $rateLimiter]);
        $stack->push($middleware);

        return $stack;
    }
}
