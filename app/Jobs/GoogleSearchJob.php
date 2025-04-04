<?php

namespace App\Jobs;

use App\Models\Profile;
use App\Services\GitHubServices;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GoogleSearchJob implements ShouldQueue
{

    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private Profile $profile)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var GitHubServices $service */
        $service = app(GitHubServices::class);

        $userName = $this->getUserName();
        if (!$userName) {
            //todo is done
            return;
        }

        $data = $service->getProfile($userName);
        //@todo different google search job?
        $links = $this->googleLinkedinSearch($data['name']);
        if ($links) {
            $data['linkedin_links'] = $links;
        }
        $data = array_merge($data, ['is_fetched' => true]);
        $this->profile->update($data);
    }
}
