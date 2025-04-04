<?php

namespace App\Services\Responses\Collections;

use App\Services\Responses\ValueObjects\GitHubUserResult;

/**
 * @template TKey of int
 * @template TModel of GitHubUserResult
 *
 * @extends AbstractCollection
 */
final class GitHubUserResultCollection extends AbstractCollection
{
    protected string $className = GitHubUserResult::class;
}
