<?php

declare(strict_types=1);

namespace App\Services;

use App\Clients\ClientInterface;

class GoogleSearchService
{
    public function __construct(private ClientInterface $client)
    {
    }

    public function search(string $keywords): array
    {
        $params = ['query' => ['q' => $keywords]];
        $response = $this->client->get('/customsearch/v1', $params);

        return json_decode($response->getBody()->getContents(), true); //@todo move to client ??
    }
}
