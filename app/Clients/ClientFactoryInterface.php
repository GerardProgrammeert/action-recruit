<?php

namespace App\Clients;

use GuzzleHttp\HandlerStack;

interface ClientFactoryInterface
{
    public function make(): ClientInterface;

    public function settings(): array;

    public function getHeaders(): array;

    public function getStack(): HandlerStack;
}
