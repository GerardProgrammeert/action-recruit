<?php

namespace App\Clients\GitHubClient;

use App\Clients\ClientFactoryInterface;
use App\Clients\ClientInterface;
use App\Clients\GuzzleClient;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

abstract class AbstractClientFactory implements ClientFactoryInterface
{
    public function __construct(
        protected string $baseUrl = 'asd',
        protected string $apiKey,
        protected string $cacheKey,
    ) {
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

    abstract public function getStack(): HandlerStack;
}
