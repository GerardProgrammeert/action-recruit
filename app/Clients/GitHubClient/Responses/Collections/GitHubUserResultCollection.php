<?php

namespace App\Clients\GitHubClient\Responses\Collections;

use App\Clients\AbstractCollection;
use App\Clients\GitHubClient\Responses\ValueObjects\GitHubUserResultValueObject;

/**
 * @template TKey of int
 * @template TModel of GitHubUserResultValueObject
 *
 * @extends AbstractCollection
 */
final class GitHubUserResultCollection extends AbstractCollection
{
    protected string $className = GitHubUserResultValueObject::class;
}
