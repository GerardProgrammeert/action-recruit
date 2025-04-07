<?php

namespace App\Jobs;

use App\Actions\Profile\StoreProfilesLinksAction;
use App\Clients\GoogleSearchClient\Responses\GoogleSearchResponse;
use App\Enums\ProfileStatusEnum;
use App\Models\Profile;
use App\Services\GoogleSearchService;

class GoogleSearchJob extends AbstractProfileJob
{
    public function handle(): void
    {
        if (!$this->profile) {
            return;
        }
        $this->start($this->profile->github_id);

        $response = $this->googleSearch($this->profile);
        (new StoreProfilesLinksAction())
            ->execute($response->getValueObject(), $this->profile->github_id, ProfileStatusEnum::GOOGLE_ENRICHED);

        $this->finish($this->profile->github_id);
    }

    private function googleSearch(Profile $profile): GoogleSearchResponse
    {
        $keyWords = $profile->name . ' netherlands site:linkedin.com';
        $service = app(GoogleSearchService::class);

        return $service->search($keyWords);
    }
}
