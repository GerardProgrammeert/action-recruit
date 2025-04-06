<?php

namespace App\Clients\GoogleSearchClient\Responses;

use App\Clients\AbstractResponse;
use App\Clients\GoogleSearchClient\ValueObjects\GoogleSearchResultValueObject;
use App\Clients\HasGetValueObjectInterface;

final class GoogleSearchResponse extends AbstractResponse implements HasGetValueObjectInterface
{
    public function getValueObject(): GoogleSearchResultValueObject
    {
        return GoogleSearchResultValueObject::hydrate($this->data['items'] ?? []);
    }
}
