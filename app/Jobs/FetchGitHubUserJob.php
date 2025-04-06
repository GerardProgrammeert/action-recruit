<?php

namespace App\Jobs;

use App\Actions\Profile\UpdateProfileAction;
use App\Services\GitHubServices;
use Illuminate\Support\Str;

class FetchGitHubUserJob extends AbstractProfileJob
{
    public function handle(GitHubServices $service): void
    {
        if (!$this->profile) {
            return;
        }

        if (!$userName = $this->getUserName()) {
            return;
        }

        $response = $service->getProfile($userName);
        $GitHubUserValueObject = $response->getValueObject();

        (new UpdateProfileAction())->execute($GitHubUserValueObject, true);

        if ($GitHubUserValueObject->getName()) {
            GoogleSearchJob::dispatch($GitHubUserValueObject->getGithubId());
        }
    }

    private function getUserName(): ?string
    {
        $user = Str::after($this->profile->url, '/users/');

        return $user === $this->profile->url ? null : $user;
    }
}
