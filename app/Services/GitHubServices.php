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
        $keywords = 'PHP type:' . UserTypeEnum::USER->value . '+location:Netherlands';
        $keywords = 'location:Netherlands PHP type:' . UserTypeEnum::USER->value;
       // $keywords = 'login:KarterMC';
       // $keywords = 'tom';
       // $keywords = 'location:Netherlands PHP in:repos+type:User';
        //$keywords = 'location:Netherlands PHP in:repos+type:' . UserType::USER->value;

dump($keywords);
        $array = $queryBuilder->toArray();
        $params = [
            'q' => urldecode($keywords),
            'per_page' => 100,
            'page' => $array['page'],
        ];
        $params = ['query' => $params];

        $rawResponse = $this->client->get(Endpoints::SEARCH_USERS->value, $params);
//dd($rawResponse->getBody()->getContents());
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
