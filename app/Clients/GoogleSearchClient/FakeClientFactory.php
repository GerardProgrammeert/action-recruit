<?php

namespace App\Clients\GoogleSearchClient;

use App\Clients\ClientFactoryInterface;
use App\Clients\ClientInterface;
use App\Clients\GuzzleClient;
use Tests\Feature\Fixtures\FakeClients\Google\GoogleFakeClient;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;

class FakeClientFactory implements ClientFactoryInterface
{
    public function __construct(private string $baseUrl, private string $apiKey, private string $cseId)
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
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    public function getStack(): HandlerStack
    {
        $stack = HandlerStack::create();
        $stack->setHandler(new GoogleFakeClient());

        $stack->push(Middleware::mapRequest(function (Request $request) {
            $uri = $request->getUri();
            $uri = $uri->withQuery(self::getDefaultQueryParams($request));
            return $request->withUri($uri);
        }));

        return $stack;
    }

    public function getDefaultQueryParams(Request $request): string
    {
        parse_str($request->getUri()->getQuery(), $existingQueryParams);

        $params = [
            'key' => $this->apiKey,
            'cx'  => $this->cseId,
        ];

        $params = array_merge($params, $existingQueryParams);

        return http_build_query($params, '', '&');
    }
}
