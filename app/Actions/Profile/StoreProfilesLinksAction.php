<?php

namespace App\Actions\Profile;

use App\Clients\GoogleSearchClient\ValueObjects\GoogleSearchResultValueObject;
use App\Enums\ProfileStatusEnum;
use App\Models\Profile;

class StoreProfilesLinksAction
{
    public function execute(GoogleSearchResultValueObject $valueObject, int $GitHubId, ProfileStatusEnum $status): void
    {
        $profile = Profile::query()->GitHubId($GitHubId)->first();

        if ($profile) {
            $profile->update(array_merge($valueObject->toArray(), ['status' => $status]));
        }
    }
}
