<?php

namespace Jobs;

use App\Jobs\GoogleSearchJob;
use App\Models\Profile;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\FeatureTestCase;

class GoogleSearchJobTest extends FeatureTestCase
{
    #[Test]
    public function it_should_fetched_and_store_links(): void
    {
        $profile = Profile::factory()->create();

        (new GoogleSearchJob($profile->github_id))->handle();
        $profile->refresh();
        $this->assertEqualsCanonicalizing($this->getData(), $profile->linkedin_links);
    }

    public function it_should_throw_error_exceeding_rate_limiter(): void
    {
        $profiles = Profile::factory()->gitHubEnriched()->count(20)->create();

        Queue::fake();

        $profiles->each(function (Profile $profile) {
            GoogleSearchJob::dispatch($profile->github_id);
        });
    }
    /**
     *@return array<int, string>
     */
    public function getData(): array
    {
        return [
            'https://nl.linkedin.com/in/dreis',
            'https://www.linkedin.com/pub/dir/+/Dreiskamper',
            'https://www.linkedin.com/pulse/php-7-has-done-wonders-language-says-stefan-phpbenelux-ahmed-khan',
            'https://nl.linkedin.com/in/denengelse',
            'https://nl.linkedin.com/posts/mvriel_netherlands3d-amsterdamtimemachine-digitaltwin-activity',
            'https://nl.linkedin.com/in/stephan-de-prouw-9b97532',
            'https://nl.linkedin.com/in/jvand64',
            'https://nl.linkedin.com/in/ivozandhuis',
            'https://nl.linkedin.com/posts/coen-van-galen-8631482a_heel-trots-dat-de-radboud-universiteit-me-activity',
            'https://nl.linkedin.com/directory/people/p-45',
        ];
    }
}
