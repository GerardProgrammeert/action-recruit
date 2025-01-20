<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Services\GitHubServices;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class GithubSearchProfiles extends Command
{
    protected $signature = 'github:search';

    protected $description = 'Search for user profiles on github';

    public function __construct(private GitHubServices $service)
    {
    }

    public function handle(): void
    {
        $keywords = 'location:Netherlands PHP in:repos';
        $profiles = $this->getProfiles($keywords);
        $this->storeProfiles($profiles);
    }

    private function getProfiles(string $keywords): Collection
    {
        $page = 0;
        $result = new Collection();
        //todo move this to do client
        //create a pagination middleware
        do {
            $page++;
            $response = $this->service->searchUsers($keywords, $page);
            $result = $result->merge($response['items']);
        } while (!empty($response['items']));

        return $result;
    }

    private function storeProfiles(Collection $profiles): void
    {
        if ($profiles->isEmpty()) {
            return;
        }

        $profiles->each(function ($profile) {
            $profile['github_id'] = $profile['id'];
            unset($profile['id']);
            Profile::updateOrCreate(
                ['github_id' => $profile['github_id']],
                $profile
            );
        });
    }
}
