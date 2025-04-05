<?php

namespace App\Actions\Profile;

use App\Actions\ActionInterface;
use App\Clients\GitHubClient\Responses\ValueObjects\GitHubUserResultValueObject;
use App\Models\Profile;

class UpdateProfileAction implements ActionInterface
{
    public function execute(GitHubUserResultValueObject $profile): void
    {
        Profile::query()->update($profile->toArray());
    }
}
