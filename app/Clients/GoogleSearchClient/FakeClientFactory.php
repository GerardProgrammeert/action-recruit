<?php

namespace App\Clients\GoogleSearchClient;

use App\Clients\ClientFactoryInterface;
use App\Clients\GoogleSearchClient\Middleware\GoogleMiddleware;
use App\Clients\GoogleSearchClient\Middleware\GoogleRateLimiterMiddleware;
use Tests\Feature\Fixtures\FakeClients\Google\GoogleFakeClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;

class FakeClientFactory extends AbstractClientFactory implements ClientFactoryInterface
{
    public function getStack(): HandlerStack
    {
        $stack = HandlerStack::create();
        $stack->setHandler(new GoogleFakeClient());

        $stack->push(Middleware::mapRequest(function (Request $request) {
            $uri = $request->getUri();
            $uri = $uri->withQuery(self::getDefaultQueryParams($request));

            return $request->withUri($uri);
        }));

        $rateLimiter = new GoogleRateLimiterMiddleware($this->cacheKey);
        $middleware = app()->makeWith(GoogleMiddleware::class, ['rateLimiter' => $rateLimiter]);
        $stack->push($middleware);

        return $stack;
    }
}
