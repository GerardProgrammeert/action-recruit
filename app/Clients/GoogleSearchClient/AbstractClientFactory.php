<?php

namespace App\Clients\GoogleSearchClient;

use App\Clients\ClientInterface;
use App\Clients\GuzzleClient;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;

abstract class AbstractClientFactory
{
    public function __construct(protected string $baseUrl, protected string $apiKey, protected string $cseId)
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

    abstract public function getStack(): HandlerStack;

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
