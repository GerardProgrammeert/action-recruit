<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
{

    /**
     *@return array<string, mixed>
     */
    public function definition(): array
    {
        $username = $this->faker->userName;

        return [
            'github_id' => time() .mt_rand(1, 1000000),
            'url' => "https://api.github.com/users/{$username}",
            'html_url' => "https://github.com/{$username}",
            'location' => $this->faker->city,
            'hireable' => $this->faker->boolean,
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'twitter_username' => $this->faker->userName,
            'blog' => $this->faker->url,
            'linkedin_links' => json_encode($this->generateLinkedInProfiles()),
            'is_fetched' => $this->faker->boolean(),
        ];
    }

    /**
     * @return array<int, string>
     */
    private function generateLinkedInProfiles(): array
    {
        $profiles = [];
        $numProfiles = rand(0, 5);

        for ($i = 0; $i < $numProfiles; $i++) {
            $profiles[] = 'https://www.linkedin.com/in/' . $this->faker->userName;
        }

        return $profiles;
    }
}
