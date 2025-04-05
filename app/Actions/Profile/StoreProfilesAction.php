<?php

namespace App\Actions\Profile;

use App\Models\Profile;
use App\Services\Responses\Collections\GitHubUserResultCollection;

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
