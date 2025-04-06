<?php

namespace App\Jobs;

use App\Models\Profile;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

abstract class AbstractProfileJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    protected ?Profile $profile;
    protected string $className;

    public function __construct(private readonly int $GithubId)
    {
        $this->profile = Profile::query()->where('github_id', '=', $GithubId)->first();
        $this->className = class_basename($this);
    }

    public function uniqueId(): int
    {
        return $this->profile->github_id;
    }

    protected function start(?string $ref): void
    {
        Log::info("Starting job $this->className" . ($ref ? " with reference: $ref" : " without a reference."));
    }

    protected function finish(?string $ref): void
    {
        Log::info("Finished job $this->className" . ($ref ? " with reference: $ref" : " without a reference."));
    }
}
