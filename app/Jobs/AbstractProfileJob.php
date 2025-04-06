<?php

namespace App\Jobs;

use App\Models\Profile;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

abstract class AbstractProfileJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    protected ?Profile $profile;

    public function __construct(private readonly int $GithubId)
    {
        $this->profile = Profile::query()->where('github_id', '=', $GithubId)->first();
    }

    /**
     * @uses \Illuminate\Bus\UniqueLock::getKey()
     */
    public function uniqueId(): int
    {
        return $this->profile->github_id;
    }
}
