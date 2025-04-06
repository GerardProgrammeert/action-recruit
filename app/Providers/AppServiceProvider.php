<?php

namespace App\Providers;

use App\Clients\ClientInterface;
use App\Clients\GitHubClient\FakeClientFactory;
use App\Clients\GitHubClient\GitHubClientFactory;
use App\Clients\GoogleSearchClient\GoogleSearchClientFactory;
use App\Services\GitHubServices;
use App\Services\GoogleSearchService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Tests\Feature\Fixtures\GitHub\FakeClient;

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
        Model::shouldBeStrict();
    }
}
