<?php

namespace App\Actions\Profile;

use App\Clients\GitHubClient\Responses\ValueObjects\GitHubUserValueObject;
use App\Models\Profile;

class UpdateProfileAction
{
    public function execute(GitHubUserValueObject $gitHubUserValueObject, ?bool $isFetch = null): void
    {
        $profile = Profile::query()->where('github_id', $gitHubUserValueObject->getGitHubId());

        if (!$profile) {
            return;
        }

        $data = $gitHubUserValueObject->toArray();
        if (!is_null($isFetch)) {
            $data['is_fetched'] = $isFetch;
        }

        $profile->update($data);
    }
}
