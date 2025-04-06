<?php

namespace App\Clients\GoogleSearchClient\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;

final readonly class GoogleSearchResultValueObject implements Arrayable
{
    /**
     *@param $links array<int, string>
     */
    public function __construct(private array $links)
    {
    }

    /**
     *@param $data array<int, <array<string, mixed>>>
     */
    public static function hydrate(array $data): ?self
    {
        $links = array_filter(array_map(self::class . '::getLink', data_get($data, '*.link')));

        return new self($links);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function toArray(): array
    {
        return [
            'linkedin_links' => $this->links,
        ];
    }

    private static function getLink(string $link): ?string
    {
        if (!filter_var($link, FILTER_VALIDATE_URL)) {
            return null;
        }

        return $link;
    }
}
