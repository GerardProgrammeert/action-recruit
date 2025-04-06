<?php

namespace Middleware;

use App\Services\GoogleSearchService;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;
use Tests\Feature\FeatureTestCase;

class TrackRequestRateLimiterTest extends FeatureTestCase
{
    private GoogleSearchService $service;
    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(GoogleSearchService::class);
    }

    #[test]
    public function it_should_throw_error_exceeding_rate_limiter(): void
    {
        $this->expectException(RuntimeException::class);

        $keywords = "Klaas";

        for ($i = 0; $i <= 50; $i++) {
            $this->service->search($keywords);
        }
    }

    #[test]
    public function it_should_fire_all_request_successfully(): void
    {
        $keywords = "Klaas";

        for ($i = 0; $i <= 2; $i++) {
            $response = $this->service->search($keywords);
            $this->assertEquals(200, $response->statusCode);
        }
    }
}
