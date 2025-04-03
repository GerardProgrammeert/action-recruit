<?php

declare(strict_types=1);

namespace App\Helpers\LoggerMiddleware;

use App\Clients\GitHubClient\GitHubClientFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ResponseLoggerMiddleware
{
    private RequestInterface $request;
    private ResponseInterface $response;

    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $this->request = $request;
            return $handler($request, $options)->then(
                function (ResponseInterface $response) {
                    $this->response = $response;
                    $body = $response->getBody()->getContents();
                    $path = $this->getFilePath();
                    $result = Storage::put("$path.json", $body);
                    dd( $result);
                    $response->getBody()->rewind();

                    return $response;
                }
            );
        };
    }

    private function getFilePath(): string
    {
        $path = Str::slug(
            Str::replace(
                '/',
                '-',
                Str::after($this->request->getUri()->getPath(), GitHubClientFactory::BASE_URL . '/')
            )
        );

        $dir = $this->getDir(GitHubClientFactory::BASE_URL);

        return $dir . '/' . $path . '-' . $this->response->getStatusCode() . '-' . time();
    }

    private function getDir($url): string
    {
        $host = parse_url($url, PHP_URL_HOST);
        $dirName = Str::replace('.','-', $host);

        if (!Storage::exists($dirName)) {
            Storage::makeDirectory($dirName);
        }

        return $dirName;
    }
}
