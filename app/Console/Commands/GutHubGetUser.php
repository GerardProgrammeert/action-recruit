<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\FetchUserJob;
use App\Models\Profile;
use Illuminate\Console\Command;

class GutHubGetUser extends Command
{
    protected $signature = 'github:get-user';

    protected $description = 'Get user information';

    public function handle(): void
    {
        $profiles = Profile::query()
            ->where('is_fetched', false)
            ->where('type', '=', 'User')
            ->take(2)->get();

        $profiles->each(function (Profile $profile) {
            FetchUserJob::dispatch($profile);
        });
    }
}
