<?php

namespace Tests\Feature\Fixtures\FakeClients\GitHub;

use App\Clients\GitHubClient\Enums\EndpointsEnum;
use GuzzleHttp\Promise\PromiseInterface;
use Tests\Feature\Fixtures\FakeClients\AbstractFakeClient;

class GitHubFakeClient extends AbstractFakeClient
{
    protected string $folderData = '/FakeClients/GitHub/Data';
    protected function getGetResponse(): ?PromiseInterface
    {
        return match ($this->getKey()) {
            EndpointsEnum::SEARCH_USERS->value => $this->getResponseFromFile('get-users-200'),
        };
    }
}
