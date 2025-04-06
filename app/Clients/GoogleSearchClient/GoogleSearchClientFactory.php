<?php

declare(strict_types=1);

namespace App\Clients\GoogleSearchClient;

use App\Clients\ClientFactoryInterface;
use App\Clients\GoogleSearchClient\Middleware\GoogleMiddleware;
use App\Clients\GoogleSearchClient\Middleware\GoogleRateLimiterMiddleware;
use App\Clients\Middleware\ResponseLoggerMiddleware;
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

        $rateLimiter = new GoogleRateLimiterMiddleware($this->cacheKey);
        $middleware = app()->makeWith(GoogleMiddleware::class, ['rateLimiter' => $rateLimiter]);
        $stack->push($middleware);

        $responseLoggerMiddleware = new ResponseLoggerMiddleware();
        $stack->push($responseLoggerMiddleware);

        return $stack;
    }
}
