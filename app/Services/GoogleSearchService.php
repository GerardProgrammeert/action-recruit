<?php

declare(strict_types=1);

namespace App\Services;

use App\Clients\ClientInterface;
use App\Clients\GoogleSearchClient\Enums\EndpointEnum;
use App\Clients\GoogleSearchClient\Responses\GoogleSearchResponse;

class GoogleSearchService
{
    public function __construct(private ClientInterface $client)
    {
    }

    public function search(string $keywords): GoogleSearchResponse
    {
        $params = ['query' => ['q' => $keywords]];

        $rawResponse = $this->client->get(EndpointEnum::CUSTOM_SEARCH->value, $params);

        return new GoogleSearchResponse($rawResponse);
    }
}
