<?php

namespace Tests\Feature\Fixtures\FakeClients\Google;

use App\Clients\GoogleSearchClient\Enums\EndpointsEnum;
use GuzzleHttp\Promise\PromiseInterface;
use Tests\Feature\Fixtures\FakeClients\AbstractFakeClient;

class GoogleFakeClient extends AbstractFakeClient
{
    protected string $folderData = '/FakeClients/Google/Data';
    protected function getGetResponse(): ?PromiseInterface
    {
        return match ($this->getKey()) {
            EndpointsEnum::CUSTOM_SEARCH->value => $this->getResponseFromFile('get-custom-search-200'),
        };
    }
}
