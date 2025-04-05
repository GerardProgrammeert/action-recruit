<?php

namespace App\Actions\Profile;

use App\Actions\ActionInterface;
use App\Models\Profile;
use App\Services\Responses\ValueObjects\GitHubUserResult;

class UpdateProfileAction implements ActionInterface
{
    public function execute(GitHubUserResult $profile): void
    {
        Profile::query()->update($profile->toArray());
    }
}
