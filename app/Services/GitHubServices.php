<?php

declare(strict_types=1);

namespace App\Services;

use App\Clients\ClientInterface;
use App\Clients\GitHubClient\Enums\EndpointsEnum;
use App\Clients\GitHubClient\GitHubSearchQueryBuilder;
use App\Clients\GitHubClient\Responses\GitHubSearchUsersResponse;
use App\Clients\GitHubClient\Responses\GitHubUserResponse;

class GitHubServices
{
    public function __construct(private ClientInterface $client)
    {
    }

    public function searchUsers(GitHubSearchQueryBuilder $queryBuilder): GitHubSearchUsersResponse
    {
        $rawResponse = $this->client->get(EndpointsEnum::SEARCH_USERS->value, $queryBuilder->toArray());

        return new GitHubSearchUsersResponse($rawResponse);
    }

    public function getProfile(string $user): GitHubUserResponse
    {
        $rawResponse = $this->client->get(EndpointsEnum::USERS->value . '/' . $user, []);

        return new GitHubUserResponse($rawResponse);
    }
}
