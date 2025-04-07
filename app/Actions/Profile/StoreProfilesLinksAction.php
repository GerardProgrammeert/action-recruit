<?php

namespace App\Actions\Profile;

use App\Clients\GoogleSearchClient\ValueObjects\GoogleSearchResultValueObject;
use App\Models\Profile;

class StoreProfilesLinksAction
{
    public function execute(GoogleSearchResultValueObject $valueObject, int $GitHubId): void
    {
        $profile = Profile::query()->GitHubId($GitHubId)->first();

        if ($profile) {
            $profile->update($valueObject->toArray());
        }
    }
}
