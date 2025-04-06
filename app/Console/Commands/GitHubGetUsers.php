<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\FetchGitHubUserJob;
use App\Models\Profile;
use Illuminate\Console\Command;

class GitHubGetUsers extends Command
{
    protected $signature = 'github:get-users {--chunkSize=50}';

    protected $description = 'Get user information from GitHub';

    protected int $count = 0;
    public function handle(): void
    {
        $chunkSize = (int) $this->option('chunkSize');
        $this->info("Starting profile fetch with chunk size: $chunkSize");
        Profile::query()
            ->isNotFetched() //@todo change flag
            ->chunk($chunkSize, function ($profilesChunk) {
                $profilesChunk->each(function (Profile $profile) {
                    FetchGitHubUserJob::dispatch($profile->github_id);
                    $this->count++;
                    $this->info("Dispatched FetchGitHubUserJob for GitHub ID: $profile->github_id");
                });
            });
        $this->info("Finished command. $this->count FetchGitHubUserJobs created.");
    }
}
