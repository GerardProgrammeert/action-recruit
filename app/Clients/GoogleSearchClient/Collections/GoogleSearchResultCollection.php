<?php

namespace App\Clients\GoogleSearchClient\Collections;

use App\Clients\AbstractCollection;
use App\Clients\GoogleSearchClient\ValueObjects\GoogleSearchResultValueObject;

/**
 * @template TKey of int
 * @template TModel of GoogleSearchResultValueObject
 *
 * @extends AbstractCollection
 */
class GoogleSearchResultCollection extends AbstractCollection
{
    protected string $className = GoogleSearchResultValueObject::class;
}
