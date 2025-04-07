<?php

namespace App\Clients;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractResponse
{
    public int $statusCode;

    public readonly bool $hasError;

    public array $data;

    public readonly string $json;

    final public function __construct(ResponseInterface $response)
    {
        $this->json = $this->getResponseJson($response);
        $this->data = $this->parseJson($this->json);
        $this->statusCode = $this->getStatusCode($response);
        $this->hasError = $this->statusCode >= 400;
    }

    protected function getResponseJson(ResponseInterface $response): string
    {
        $body = $response->getBody();
        $body->rewind();

        return $body->getContents();
    }

    protected function getStatusCode(ResponseInterface $response): int
    {
        return $response->getStatusCode();
    }

    protected function parseJson(?string $json): array
    {
        if ($json === '' || $json === null || !Str::isJson($json)) {
            return [];
        }

        try {
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

            if (isset($data['message'])) {
                throw new ErrorResponseException($this->parseErrorMessage($data));
            }

            return $data;
        } catch (\JsonException $e) {
            throw new ErrorResponseException('Failed to decode JSON: ' . $e->getMessage());
        }
    }

    public function isIncomplete(): bool
    {
        if ($this->data['items'] ?? false){
            return false;
        }

        if (count($this->data['items']) === 0){
            return false;
        }

        return true;
    }

    protected function parseErrorMessage(array $data): string
    {
        $errorMessage = $data['message'] ?? 'Unknown error occurred';

        if (isset($data['errors']) && is_array($data['errors'])) {
            $details = array_map(fn($error) => $error['message'] ?? json_encode($error), $data['errors']);
            $errorMessage .= ' | Details: ' . implode(', ', $details);
        }

        return $errorMessage;
    }
}
