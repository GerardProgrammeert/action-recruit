<?php

declare(strict_types=1);

namespace App\Services;

use App\Clients\ClientInterface;

class GitHubServices
{
    public function __construct(private ClientInterface $client)
    {
    }

    public function searchUsers(string $keywords, int $offset): array
    {
        $params = [
            'q' => $keywords,
            'per_page' => 100,
            'page' => $offset,
        ];
        $params = ['query' => $params];
        $response = $this->client->get('/search/users', $params);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getProfile(string $user): array
    {
        $response = $this->client->get('/users/' . $user, []);

        return json_decode($response->getBody()->getContents(), true);
    }
}
