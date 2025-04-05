<?php

namespace App\Actions\Profile;

use App\Clients\GitHubClient\Responses\Collections\GitHubUserResultCollection;
use App\Models\Profile;

class StoreProfilesAction
{
    public function execute(GitHubUserResultCollection $profiles) : void
    {
        if ($profiles->isEmpty()) {
            return;
        }

        $profiles->chunk(500)->each(function ($chunk) {
            Profile::query()->upsert($chunk->toArray(), ['github_id']);
        });
    }
}
