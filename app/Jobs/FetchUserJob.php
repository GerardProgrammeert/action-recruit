<?php

namespace App\Jobs;

use App\Models\Profile;
use App\Services\GitHubServices;
use App\Services\GoogleSearchService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FetchUserJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private Profile $profile)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var GitHubServices $service */
        $service = app(GitHubServices::class);

        $userName = $this->getUserName();
        if (!$userName) {
            return;
        }

        $data = $service->getProfile($userName);

        $links = $this->googleLinkedinSearch($data['name']);
        if ($links) {
            $data['linkedin_links'] = $links;
        }
        $data = array_merge($data, ['is_fetched' => true]);
        $this->profile->update($data);
    }

    private function getUserName(): ?string
    {
        if (!$this->profile->url) {
            return null;
        }

        $user = Str::after($this->profile->url, '/users/');

        return $user === $this->profile->url ? null : $user;
    }
    private function googleLinkedinSearch(string $fullName): ?array
    {


        $keyWords = $fullName . ' netherlands site:linkedin.com';

        // $apiKey = config('google.api_key');
        // $cseId = config('google.cse_id');
        // $url = "https://www.googleapis.com/customsearch/v1?q=$query&key=$apiKey&cx=$cseId&gl=nl";
        // $response = Http::get($url);
        // $result = $response->json();

        $service = app(GoogleSearchService::class);

        $result = $service->search($keyWords);
        dd($result);
        $links = [];
        if (isset($result['items'])) {
            foreach ($result['items'] as $item) {
                if (strpos($item['link'], 'linkedin.com') !== false) {
                    $links[] = $item['link'];
                }
            }
        }

        return empty($links) ? null : $links;
    }
}
