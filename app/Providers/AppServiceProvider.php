<?php

namespace App\Providers;

use App\Clients\ClientInterface;
use App\Clients\GitHubClient\GitHubClientFactory;
use App\Clients\GoogleSearchClient\GoogleSearchClientFactory;
use App\Services\GitHubServices;
use App\Services\GoogleSearchService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app()->when(GoogleSearchService::class)
            ->needs(ClientInterface::class)
            ->give(function (): ClientInterface {
                return GoogleSearchClientFactory::make();
            });

        app()->when(GitHubServices::class)
            ->needs(ClientInterface::class)
            ->give(function (): ClientInterface {
                return GitHubClientFactory::make();
            });
    }
}
