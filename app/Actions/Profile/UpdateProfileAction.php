<?php

namespace App\Actions\Profile;

use App\Clients\GitHubClient\Responses\ValueObjects\GitHubUserValueObject;
use App\Enums\ProfileStatusEnum;
use App\Models\Profile;

class UpdateProfileAction
{
    public function execute(
        GitHubUserValueObject $gitHubUserValueObject,
        ProfileStatusEnum $status = ProfileStatusEnum::UNPROCESSED
    ): void {
        $profile = Profile::query()->GitHubId($gitHubUserValueObject->getGitHubId())->first();

        if (!$profile) {
            return;
        }

        $profile->update(array_merge($gitHubUserValueObject->toArray(), ['status' => $status->value]));
    }
}
