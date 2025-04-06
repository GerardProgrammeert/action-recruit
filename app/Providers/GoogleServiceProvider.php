<?php

namespace App\Providers;

use App\Clients\ClientInterface;
use App\Clients\GoogleSearchClient\GoogleSearchClientFactory;
use App\Clients\GoogleSearchClient\FakeClientFactory;
use App\Services\GoogleSearchService;
use Illuminate\Support\ServiceProvider;

class GoogleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        app()->when(GoogleSearchService::class)
            ->needs(ClientInterface::class)
            ->give(function (): ClientInterface {
                $args = [
                    'baseUrl' =>  env('GOOGLE_API_URL'),
                    'apiKey' => env('GOOGLE_API_KEY'),
                    'cseId' => env('GOOGLE_CSE_ID'),
                ];

                if ($this->app->environment('testing')) {
                    return (new FakeClientFactory(...$args))->make();
                }
                return (new GoogleSearchClientFactory(...$args))->make();
            });
    }
}
