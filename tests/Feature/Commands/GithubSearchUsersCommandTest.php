<?php

namespace Tests\Feature\Commands;

use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Fixtures\FakeClients\AbstractFakeClient;

class GithubSearchUsersCommandTest extends CommandTest
{
    protected string $signature = 'github:search-users "PHP"';

    #[Test]
    public function it_should_fetch_and_store_users(): void
    {
        $this->getCommand()
            ->assertSuccessful()
            ->run();

        $this->assertDatabaseHas('profiles', $this->getData());
    }

    #[Test]
    public function it_should_handle_exceptions(): void
    {
        AbstractFakeClient::fakeResponse(500,'',[],true);
        $this->artisan('github:search-users "PHP"')
            ->expectsOutput('Error occurred while fetching page 1: Internal Server Error')
            ->assertExitCode(0);

    }

    /**
     * @return array<string, int|string|null>
     */
    private function getData(): array
    {
        return [
            'github_id' => 15669080,
            'url' => 'https://api.github.com/users/PHPirates',
            'html_url' => 'https://github.com/PHPirates',
            'location' => null,
            'hireable' => null,
            'name' => null,
            'email' => null,
            'twitter_username' => null,
            'blog' => null,
            'is_fetched' => 0,
            'linkedin_links' => null,
        ];
    }
}
