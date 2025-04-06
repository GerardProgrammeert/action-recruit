<?php

namespace App\Clients\GitHubClient\Responses\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;

final readonly class GitHubUserResultValueObject implements Arrayable
{
    public function __construct(private int $github_id, private ?string $url = null, private ?string $htmlUrl = null)
    {
    }

    public static function hydrate(array $data): self
    {
        $args = [
            'github_id' => self::getId($data),
            'url' => $data['url'] ?? null,
            'htmlUrl' => $data['html_url'] ?? null,
        ];

        return new self(...$args);
    }

    /**
     *@return array<string, string>
     */
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
}
