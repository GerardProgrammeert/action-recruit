<?php

namespace App\Services\Responses\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;

class GitHubUserResult implements Arrayable
{
    private int $github_id;
    private ?string $url;
    private ?string $htmlUrl;

    public static function hydrate(array $data): self
    {
        $instance = new self();

        $instance->github_id = self::getId($data);
        $instance->url = $data['url'] ?? null;
        $instance->htmlUrl = $data['html_url'] ?? null;

        return $instance;
    }

    public function toArray(): array
    {
        return [
            'github_id' => $this->github_id,
            'url' => $this->url,
            'html_url' => $this->htmlUrl,
        ];
    }

    private static function getId(array $data): int
    {
        if (!isset($data['id']) || !is_int($data['id'])) {
            throw new InvalidArgumentException('GitHub user ID is required and must be an integer.');
        }

        return $data['id'];
    }

    private static function getType(array $data): ?UserType
    {
        return UserType::tryFrom($data['type'] ?? null);
    }
}
