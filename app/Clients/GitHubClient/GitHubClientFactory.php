<?php

declare(strict_types=1);

namespace App\Clients\GitHubClient;

use App\Clients\ClientFactoryInterface;
use App\Clients\GitHubClient\Middleware\GitHubMiddleware;
use App\Clients\GitHubClient\Middleware\GitHubRateLimiterMiddleware;
use App\Clients\Middleware\ResponseLoggerMiddleware;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;

final class GitHubClientFactory extends AbstractClientFactory implements ClientFactoryInterface
{
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
