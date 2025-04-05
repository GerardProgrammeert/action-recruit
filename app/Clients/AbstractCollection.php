<?php

namespace App\Clients;

use Illuminate\Support\Collection;

abstract class AbstractCollection extends Collection
{
    /**@var class-string */
    protected string $className;

    public static function hydrate(array $items): static
    {
        $collection = new static();

        foreach ($items as $item) {
            $collection->add($collection->className::hydrate($item));
        }

        return $collection;
    }
}
