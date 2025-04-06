<?php

namespace App\Clients;

use Illuminate\Support\Collection;

abstract class AbstractCollection extends Collection
{
    /**@var $className class-string */
    protected string $className;

    /**
     *@param $items array<int, mixed>
     */
    public static function hydrate(array $items): static
    {
        $collection = new static();// @phpstan-ignore-line:

        foreach ($items as $item) {
            if ($collection->className::hydrate($item)) {
                $collection->add($collection->className::hydrate($item));
            }
        }

        return $collection;
    }
}
