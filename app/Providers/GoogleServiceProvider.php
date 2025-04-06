<?php

namespace App\Providers;

use App\Clients\ClientInterface;
use App\Clients\GoogleSearchClient\GoogleSearchClientFactory;
use App\Services\GoogleSearchService;
use Illuminate\Support\ServiceProvider;

class GoogleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        app()->when(GoogleSearchService::class)
            ->needs(ClientInterface::class)
            ->give(function (): ClientInterface {
                return (new GoogleSearchClientFactory(
                    env('GOOGLE_API_URL'),
                    env('GOOGLE_API_KEY'),
                    env('GOOGLE_CSE_ID')
                )
                )->make();
            });
    }
}
