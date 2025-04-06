<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\FetchGitHubUserJob;
use App\Models\Profile;
use Illuminate\Console\Command;

class GitHubGetUsers extends Command
{
    protected $signature = 'github:get-users {--chunkSize=50 : How many items to process}';

    protected $description = 'Get user information from GitHub';

    public function handle(): void
    {
        $chunkSize = (int) $this->option('chunkSize');

        Profile::query()
            ->isNotFetched() //@todo change flag
            ->chunk($chunkSize, function ($profilesChunk) {
                $profilesChunk->each(function (Profile $profile) {
                    FetchGitHubUserJob::dispatch($profile->github_id);
                });
            });
    }
}
