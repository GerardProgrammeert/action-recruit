<?php

namespace App\Clients\GitHubClient\Responses;

use App\Clients\AbstractResponse;
use App\Clients\GitHubClient\Responses\ValueObjects\GitHubUserValueObject;
use App\Clients\HasGetValueObjectInterface;

class GitHubUserResponse extends AbstractResponse implements HasGetValueObjectInterface
{
    public function getValueObject(): GitHubUserValueObject
    {
        return GitHubUserValueObject::hydrate($this->data);
    }
}
