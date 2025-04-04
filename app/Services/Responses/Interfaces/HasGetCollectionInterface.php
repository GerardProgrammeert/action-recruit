<?php

namespace App\Services\Responses\Interfaces;

use App\Services\Responses\Collections\AbstractCollection;

interface HasGetCollectionInterface
{
    public function getCollection(): AbstractCollection;
}
