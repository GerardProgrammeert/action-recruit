<?php

namespace App\Providers;

use App\Clients\ClientInterface;
use App\Clients\GitHubClient\FakeClientFactory;
use App\Clients\GitHubClient\GitHubClientFactory;
use App\Services\GitHubServices;
use Illuminate\Support\ServiceProvider;

class GitHubServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        app()->when(GitHubServices::class)
            ->needs(ClientInterface::class)
            ->give(function (): ClientInterface {
                $args = [
                    'baseUrl' => env('GITHUB_API_URL'),
                    'apiKey' => env('GITHUB_API_KEY'),
                    'cacheKey' => env('GITHUB_CACHE_KEY_RATE_LIMITER'),
                ];
                if ($this->app->environment('testing')) {
                    return (new FakeClientFactory(...$args))->make();
                }
                return (new GitHubClientFactory(...$args))->make();
            });
    }
}
