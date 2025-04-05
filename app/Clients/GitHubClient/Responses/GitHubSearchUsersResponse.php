<?php

namespace App\Clients\GitHubClient\Responses;

use App\Clients\AbstractResponse;
use App\Clients\GitHubClient\Responses\Collections\GitHubUserResultCollection;
use App\Clients\HasGetCollectionInterface;

final class GitHubSearchUsersResponse extends AbstractResponse implements HasGetCollectionInterface
{
   public function getCollection(): GitHubUserResultCollection
   {
        return GitHubUserResultCollection::hydrate($this->data['items'] ?? []);
   }
}
