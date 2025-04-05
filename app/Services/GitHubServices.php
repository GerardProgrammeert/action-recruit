<?php

declare(strict_types=1);

namespace App\Services;

use App\Clients\ClientInterface;
use App\Clients\GitHubClient\Endpoints;
use App\Clients\GitHubClient\Enums\UserTypeEnum;
use App\Clients\GitHubClient\GitHubSearchQueryBuilder;
use App\Clients\GitHubClient\Responses\GitHubSearchUsersResponse;

class GitHubServices
{
    public function __construct(private ClientInterface $client)
    {
    }

    public function searchUsers(GitHubSearchQueryBuilder $queryBuilder): GitHubSearchUsersResponse
    {
        $rawResponse = $this->client->get(Endpoints::SEARCH_USERS->value,  $queryBuilder->toArray());

        return new GitHubSearchUsersResponse($rawResponse);
    }

    public function getProfile(string $user): array
    {
        $response = $this->client->get(Endpoints::USERS->value . '/' . $user, []);

        return json_decode($response->getBody()->getContents(), true);
    }
}
