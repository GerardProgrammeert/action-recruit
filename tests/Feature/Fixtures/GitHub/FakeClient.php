<?php

namespace Tests\Feature\Fixtures\GitHub;

use App\Clients\GitHubClient\Enums\EndpointsEnum;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use LogicException;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Promise\Create;

/**
 * @codeCoverageIgnore
 */
final class FakeClient
{
    private Collection $query;

    private string $method;

    private string $path;

    /** @var array<string, mixed> */
    private array $payload;

    public static $nextResponse = null;

    public static $shouldThrow = false;

    private function setProperties(RequestInterface $request): void
    {
        parse_str($request->getUri()->getQuery(), $query);
        $this->query = collect($query);
        $this->method = $request->getMethod();
        $this->path = $this->getPath($request);
        $this->payload = (array)json_decode($request->getBody()->getContents(), true);
    }

    public function __invoke(RequestInterface $request): PromiseInterface
    {
        $this->setProperties($request);

        if (self::$shouldThrow) {
            return Create::rejectionFor(
                new RequestException('Internal Server Error', $request, self::$nextResponse)
            );
        }

        if ($this->method === 'GET' && $getResponse = $this->getGetResponse()) {
            return $getResponse;
        }

        throw new LogicException("Github endpoint not faked: $this->method $this->path");
    }

    private function getGetResponse(): ?PromiseInterface
    {
        return match ($this->getKey()) {
            EndpointsEnum::SEARCH_USERS->value => $this->getResponseFromFile('get-users-200'),
        };
    }

    private function getKey(): string
    {
        if ($this->query->get('filtervalues') && $this->query->get('filterfieldids')) {
            return $this->path . '|{id}';
        }

        return '/' .$this->path;
    }

    private function getPath(RequestInterface $request): string
    {
        $path = Str::after(rtrim($request->getUri()->getPath(), '/'), '/');
        $path = Str::replace(['404', '500'], ['{not-found}', '{fail}'], $path);

        $numbersPattern = '/\/(\d)+/m';

        return ltrim((string)preg_replace($numbersPattern, '/{id}', $path), '/');
    }

    private function getResponseFromFile(string $fileName, int $statusCode = 200): FulfilledPromise
    {
        // set variables based on the query parameters
        $query = $this->query->toArray();
        extract($query, EXTR_PREFIX_SAME, 'query');
        // now `$email` exists, when the query was: ['email' => 'foo']

        $rootPath = dirname(__DIR__);

        $json = file_get_contents( $rootPath . '/GitHub/Data/' . $fileName . '.json');

        return new FulfilledPromise(
            new Response($statusCode, ['Content-Type' => 'application/json'], $json)
        );
    }

    public static function fakeResponse($status = 200, $body = '', array $headers = [], $throw = false)
    {
        self::$nextResponse = new Response($status, $headers, $body);
        self::$shouldThrow = $throw;
    }
}
