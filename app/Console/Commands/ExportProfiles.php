<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Helpers\CSV\CSVExporter;
use App\Jobs\FetchUserJob;
use App\Models\Profile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExportProfiles extends Command
{
    protected $signature = 'profiles:export';

    protected $description = 'Export profiles';

    public function handle(): void
    {

        $path = 'profiles_' .  time() . '.csv';
        Storage::put($path, '');
        $data = Profile::query()->lazy();
        $exporter = new CSVExporter(
            $data,
            Storage::path($path),
            function ($profile) {
                return [
                    'id' => $profile->id,
                    'github_id' => $profile->github_id,
                    'type' => $profile->type,
                    'url' => $profile->url,
                    'html_url' => $profile->html_url,
                    'location' => $profile->location,
                    'hireable' => $profile->hireable,
                    'name' => $profile->name,
                    'email' => $profile->email,
                    'twitter_username' => $profile->twitter_username,
                    'blog' => $profile->blog,
                    'is_done' => $profile->is_done,
                    'linkedin_links' => $profile->linkedin_links ? implode('|', $profile->linkedin_links) : '' ,
                    'is_fetched' => $profile->is_fetched,
                ];
            }
        );

        $exporter->export();
    }
}
