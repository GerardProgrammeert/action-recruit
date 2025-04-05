<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\FetchGitHubUserJob;
use App\Jobs\GoogleSearchJob;
use App\Models\Profile;
use Illuminate\Console\Command;

class GitHubGetUser extends Command
{
    protected $signature = 'github:get-user';

    protected $description = 'Get user information';

    public function handle(): void
    {
        //@todo can create jobs for all not fetched github profiles
        $profiles = Profile::query()
            ->where('is_fetched', false)
            ->where('type', '=', 'User')
            ->take(2)->get();

        $profiles->each(function (Profile $profile) {
            $fetchUserJob = new FetchGitHubUserJob($profile->id);

            $fetchUserJob->chain([
                new GoogleSearchJob($profile),
            ]);

            dispatch($fetchUserJob);
        });
    }
}
