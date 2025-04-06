<?php

namespace App\Clients\GitHubClient\Responses\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;

final readonly class GitHubUserValueObject implements Arrayable
{
    public function __construct(
        private int $github_id,
        private ?string $location = null,
        private ?bool $hireable = null,
        private ?string $email = null,
        private ?string $name = null,
        private ?string $twitter_username = null,
        private ?string $blog = null,
    ) {
    }

    /**
     *@param array<string, mixed> $data
     */
    public static function hydrate(array $data): self
    {
        $args = [
            'github_id' => self::getId($data),
            'location' => $data['location'] ?? null,
            'hireable' => $data['hireable'] ?? null,
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'twitter_username' => $data['twitter_username'] ?? null,
            'blog' => $data['blog'] ?? null,
        ];

        return new self(...$args);
    }

    /**
     * @return array<string, string|bool|null>
     */
    public function toArray(): array
    {
        return [
            'github_id' => $this->github_id,
            'location' => $this->location,
            'hireable' => $this->hireable,
            'name' => $this->name,
            'email' => $this->email,
            'twitter_username' => $this->twitter_username,
            'blog' => $this->blog,
        ];
    }

    public function getGithubId(): int
    {
        return $this->github_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    private static function getId(array $data): int
    {
        if (!isset($data['id']) || !is_int($data['id'])) {
            throw new InvalidArgumentException('GitHub user ID is required and must be an integer.');
        }

        return $data['id'];
    }
}
