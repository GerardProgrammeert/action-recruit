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

final readonly class GoogleSearchClientFactory implements ClientFactoryInterface
{
    public function __construct(private string $baseUrl, private string $apiKey, private string $csiId)
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
            'debug'    => true,
        ];
    }

    public function getHeaders(): array
    {
        return [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

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

    public function getDefaultQueryParams(Request $request): string
    {
        parse_str($request->getUri()->getQuery(), $existingQueryParams);

        $params = [
            'key' => $this->apiKey,
            'cx'  => $this->csiId,
        ];

        $params = array_merge($params, $existingQueryParams);

        return http_build_query($params, '', '&');
    }
}
