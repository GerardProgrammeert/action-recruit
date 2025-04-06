<?php

namespace App\Clients\GitHubClient;

use App\Clients\ClientInterface;
use App\Clients\GuzzleClient;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Tests\Feature\Fixtures\FakeClients\GitHub\GitHubFakeClient;

class FakeClientFactory
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

        $stack->setHandler(new GitHubFakeClient());

        return $stack;
    }
}
