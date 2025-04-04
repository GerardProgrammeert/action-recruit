<?php

declare(strict_types=1);

namespace App\Clients\GitHubClient;

use App\Clients\ClientInterface;
use App\Clients\GuzzleClient;
use App\Helpers\LoggerMiddleware\ResponseLoggerMiddleware;
use App\Helpers\RateLimitingMiddleware\GitHubRateLimiterMiddleware;
use App\Helpers\RateLimitingMiddleware\HeaderRateLimiter\GitHubRateLimiter;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use RuntimeException;

class GitHubClientFactory
{
    public const BASE_URL = 'https://api.github.com/';

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
            'Authorization' => 'token ' . self::getApiKey(),
            'Accept'        => 'application/vnd.github.v3+json',
        ];
    }

    private static function getStack(): HandlerStack
    {
        $stack = HandlerStack::create();

        $handler = new CurlHandler();
        $stack->setHandler($handler);

        $rateLimiter = new GitHubRateLimiter();
        $middleware = app()->makeWith(GitHubRateLimiterMiddleware::class, ['rateLimiter' => $rateLimiter]);

        $stack->push($middleware);


        $responseLoggerMiddleware = new ResponseLoggerMiddleware();
        $stack->push($responseLoggerMiddleware);

        return $stack;
    }

    private static function getApiKey(): string
    {
        if (!$key = config('github.api_key')) {
            throw new RuntimeException('No API Key for Github client provided ');
        }

        return $key;
    }
}
