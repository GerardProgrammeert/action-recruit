<?php

declare(strict_types=1);

namespace App\Clients;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class GuzzleClient implements ClientInterface
{
    public function __construct(public Client $client)
    {
    }

    public function get(string $url, array $params): ResponseInterface
    {
        return $this->client->get($url, $params);
    }

    public function post(string $url, array $payload = []): ResponseInterface
    {
        return $this->client->post($url, $payload);
    }

    public function put(string $url, array $payload = []): ResponseInterface
    {
        return $this->client->put($url, $payload);
    }

    public function patch(string $url, array $payload = []): ResponseInterface
    {
        return $this->client->patch($url, $payload);
    }

    public function delete(string $url): ResponseInterface
    {
        return $this->client->delete($url);
    }
}
