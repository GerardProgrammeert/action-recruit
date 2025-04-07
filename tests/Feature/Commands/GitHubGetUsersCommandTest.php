<?php

namespace Tests\Feature\Commands;

use App\Enums\ProfileStatusEnum;
use App\Jobs\FetchGitHubUserJob;
use App\Models\Profile;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;

class GitHubGetUsersCommandTest extends CommandTest
{
    protected string $signature = 'github:get-users';

    #[Test]
    public function it_should_dispatch_job_successfully(): void
    {
        Queue::fake();

        Profile::factory()->count(500)->create(['status' => ProfileStatusEnum::UNPROCESSED->value]);

        $this->getCommand()
            ->assertSuccessful()
            ->run();

        Queue::assertPushed(FetchGitHubUserJob::class, 500);
    }
}
