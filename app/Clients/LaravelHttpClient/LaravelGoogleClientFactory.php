<?php

declare(strict_types=1);

namespace App\Clients\LaravelHttpClient;

use App\Clients\ClientInterface;
use App\Helpers\RateLimitingMiddleware\RateLimitingMiddleWare;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Http;

const LIMIT = 100;

class LaravelGoogleClientFactory
{
    public static function make(): ClientInterface
    {
        // Http::macro('GoogleSearch', function () {
        //     $apiKey = config('services.google.api_key');
        //     $cseId = config('services.google.cse_id');
        //
        //     return Http::baseUrl('https://www.googleapis.com/customsearch/v1')
        //         ->withHeaders([
        //                           'Accept' => 'application/json',
        //                       ])
        //         ->withQueryParameters([
        //                                   'key' => $apiKey,
        //                                   'cx' => $cseId,
        //                               ])
        //         ->withRequestMiddleware(function (Request $request) {
        //             RateLimitingMiddleWare::countRequests('GoogleSearch', LIMIT);
        //             return $request;
        //         });
        // });

        $apiKey = config('services.google.api_key');
        $cseId = config('services.google.cse_id');

        Http::baseUrl('https://www.googleapis.com/customsearch/v1')
            ->withHeaders([
                              'Accept' => 'application/json',
                          ])
            ->withQueryParameters([
                                      'key' => $apiKey,
                                      'cx'  => $cseId,
                                  ])
            ->withRequestMiddleware(function (Request $request) {
                RateLimitingMiddleWare::countRequests('GoogleSearch', LIMIT);
                return $request;
            });

        $client = Http::buildClient();

        return new LaravelHttpClient($client);
    }
}
