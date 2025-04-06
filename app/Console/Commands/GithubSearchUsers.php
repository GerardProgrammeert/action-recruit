<?php

namespace App\Console\Commands;

use App\Actions\Profile\StoreProfilesAction;
use App\Clients\GitHubClient\Enums\UserTypesEnum;
use App\Clients\GitHubClient\GitHubSearchQueryBuilder;
use App\Clients\GitHubClient\Responses\Collections\GitHubUserResultCollection;
use App\Services\GitHubServices;
use Illuminate\Console\Command;

use function Laravel\Prompts\text;

class GithubSearchUsers extends Command
{
    protected $signature = 'github:search-users {keywords? : The keywords to search for on GitHub}';

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
        (new StoreProfilesAction())->execute($profiles);
        $this->info('Finished searching');
        $this->info("{$profiles->count()} profiles found.");
    }

    private function getProfiles(string $keywords): GitHubUserResultCollection
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
            } catch (\Exception $e) {
                $this->error("Error occurred while fetching page $page: " . $e->getMessage());
                break;
            }
        } while (!$response->isInComplete());

        return $collection;
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

    private function getQueryBuilder(string $keywords, int $offset): GitHubSearchQueryBuilder
    {
        $queryBuilder = new GitHubSearchQueryBuilder();
        $queryBuilder->setKeywords($keywords);
        $queryBuilder->setOffset($offset);
        $queryBuilder
            ->where('type', '=', UserTypesEnum::USER->value)
            ->where('location', '=', 'Netherlands')
            ->where('repos', '>', 10);

        return $queryBuilder;
    }
}
