<?php

declare(strict_types=1);

namespace App\Clients\GitHubClient;

use App\Clients\ClientFactoryInterface;
use App\Clients\ClientInterface;
use App\Clients\GitHubClient\Middleware\GitHubMiddleware;
use App\Clients\GitHubClient\Middleware\GitHubRateLimiterMiddleware;
use App\Clients\GuzzleClient;
use App\Clients\Middleware\ResponseLoggerMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;

final readonly class GitHubClientFactory implements ClientFactoryInterface
{
    public function __construct(private string $baseUrl, private string $apiKey)
    {
    }

    public function make(): ClientInterface
    {
        $client = new Client($this->settings());

        return new GuzzleClient($client);
    }

    public function settings(): array
    {
        return [
            'base_uri' => $this->baseUrl,
            'headers'  => $this->getHeaders(),
            'handler'  => $this->getStack(),
        ];
    }

    public function getHeaders(): array
    {
        return [
            'Authorization' => 'token ' . $this->apiKey,
            'Accept'        => 'application/vnd.github.v3+json',
        ];
    }

    public function getStack(): HandlerStack
    {
        $stack = HandlerStack::create();

        $handler = new CurlHandler();
        $stack->setHandler($handler);

        $rateLimiter = new GitHubRateLimiterMiddleware();
        $middleware = app()->makeWith(GitHubMiddleware::class, ['rateLimiter' => $rateLimiter]);

        $stack->push($middleware);


        $responseLoggerMiddleware = new ResponseLoggerMiddleware();
        $stack->push($responseLoggerMiddleware);

        return $stack;
    }
}
