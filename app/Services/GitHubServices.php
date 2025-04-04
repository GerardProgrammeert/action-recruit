<?php

declare(strict_types=1);

namespace App\Services;

use App\Clients\ClientInterface;
use App\Services\Responses\GitHubSearchUsersResponse;

class GitHubServices
{
    public function __construct(private ClientInterface $client)
    {
    }

    public function searchUsers(string $keywords, int $offset): GitHubSearchUsersResponse
    {
        $keywords = $keywords . ' type:' . UserType::USER->value;

        $params = [
            'q' => $keywords,
            'per_page' => 100,
            'page' => $offset,
        ];
        $params = ['query' => $params];

        $rawResponse = $this->client->get(Endpoints::SEARCH_USERS->value, $params);

        return new GitHubSearchUsersResponse($rawResponse);
    }

    private function buildQuery(string $keywords, string $userType): string
    {
        return $keywords . ' type:' . $userType;
    }

    public function getProfile(string $user): array
    {
        $response = $this->client->get(Endpoints::USERS->value . '/' . $user, []);

        return json_decode($response->getBody()->getContents(), true);
    }
}
