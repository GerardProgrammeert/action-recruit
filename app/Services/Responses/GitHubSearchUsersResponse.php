<?php

namespace App\Services\Responses;

use App\Services\Responses\Collections\GitHubUserResultCollection;
use App\Services\Responses\Interfaces\HasGetCollectionInterface;

final class GitHubSearchUsersResponse extends AbstractResponse implements HasGetCollectionInterface
{
   public function getCollection(): GitHubUserResultCollection
   {
        return GitHubUserResultCollection::hydrate($this->data['items'] ?? []);
   }
}
