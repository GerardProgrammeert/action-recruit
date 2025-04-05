<?php

declare(strict_types=1);

namespace App\Clients\Middleware;

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
                    $path = $this->getFilePath();
                    Storage::put("$path.json", $this->response->getBody()->getContents());
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
                Str::after($this->request->getUri()->getPath(), $this->gethost() . '/')
            )
        );
        $timestamp = round(microtime(true) * 1000);

        return $this->getDir() . '/' . $path . '-' . $this->response->getStatusCode() . '-' . $timestamp;
    }

    private function getDir(): string
    {
        $host = parse_url($this->gethost(), PHP_URL_HOST);
        $dirName = Str::replace('.', '-', $host);

        if (!Storage::exists($dirName)) {
            Storage::makeDirectory($dirName);
        }

        return $dirName;
    }

    private function getHost(): string
    {
        return $this->request->getUri()->getHost();
    }
}
