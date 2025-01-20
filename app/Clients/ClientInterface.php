<?php

declare(strict_types=1);

namespace App\Clients;

use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    public function get(string $url, array $params): ResponseInterface;
    public function post(string $url, array $payload = []): ResponseInterface;
    public function put(string $url, array $payload = []): ResponseInterface;
    public function patch(string $url, array $payload = []): ResponseInterface;
    public function delete(string $url): ResponseInterface;
}
