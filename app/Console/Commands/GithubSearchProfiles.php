<?php

namespace App\Console\Commands;

use App\Actions\Profile\StoreProfilesAction;
use App\Models\Profile;
use App\Services\GitHubSearchQueryBuilder;
use App\Services\GitHubServices;
use App\Services\Responses\Collections\GitHubUserResultCollection;
use App\Services\UserType;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use function Laravel\Prompts\text;

class GithubSearchProfiles extends Command
{
    protected $signature = 'github:search {keywords? : The keywords to search for on GitHub}';

    protected $description = 'Search GitHub for user profiles matching the provided keywords.';

    public function __construct(private readonly GitHubServices $service)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $keywords = $this->getKeywords();

        $this->info('Start searching for profiles containing ' . $keywords);
        $this->output->write('Searching profiles');
        $profiles = $this->getProfiles($keywords);
        (new StoreProfilesAction($profiles)->execute();
        $this->info('Finished searching');
        $this->info("{$profiles->count()} profiles found.");
    }

    private function getProfiles(string $keywords): Collection
    {
        $page = 1;
        $collection = new GitHubUserResultCollection();
        do {
            try {
                $this->output->write('.');
                $response = retry(3, function () use ($keywords, $page) {
                    return $this->service->searchUsers($this->getQueryBuilder($keywords, $page));
                }, 1000);
                $items = $response->getCollection();
                $collection = $collection->merge($items);
                $page++;
            }
            catch (\Exception $e) {
                $this->error("Error occurred while fetching page $page: " . $e->getMessage());
                break;
            }
        } while ($response->hasItems());

        return $collection;
    }

    private function storeProfiles(Collection $profiles): void
    {
        if ($profiles->isEmpty()) {
            return;
        }

        $profiles->chunk(500)->each(function ($chunk) {
            Profile::query()->upsert($chunk->toArray(), ['github_id']);
        });
    }

    public function getKeywords(): string
    {
        $keywords = $this->argument('keywords');

        if (empty($keywords)) {
            $keywords = text(
                label: 'Please provide keyword(s) to search GitHub profiles!',
                placeholder: 'PHP',
                required: true
            );
        }

        return $keywords;
    }

    private function getQueryBuilder(string $keywords, int $page): GitHubSearchQueryBuilder
    {
        $queryBuilder = new GitHubSearchQueryBuilder();
        $queryBuilder->setKeywords($keywords);
        $queryBuilder->setPage($page);
        $queryBuilder
            ->where('type', '=', UserType::USER->value)
            ->where('location', '=', 'Netherlands');
           // ->where('repos', '>' , 20000);

        return $queryBuilder;
    }
}
