<?php

declare(strict_types=1);

namespace App\Shared\Http;

use App\Shared\Typed\Typed;
use Xtompie\Container\Container;
use Xtompie\Container\Provider;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\UploadedFile;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Xtompie\Result\ErrorCollection;

final class Request extends ServerRequest implements Provider
{
    /**
     * @var array<string,mixed>|null
     */
    private ?array $resolvedBody = null;

    public static function provide(string $abstract, Container $container): object
    {
        /** @var array<string, array{name: string, type: string, tmp_name: string, error: int, size: int}> $_FILES */
        return ServerRequestFactory::fromGlobals(
            server: $_SERVER,
            query: $_GET,
            body: $_POST,
            cookies: $_COOKIE,
            files: $_FILES,
        );
    }

    /**
     * @return array<string,mixed>
     */
    public function query(): array
    {
        return $this->getQueryParams();
    }

    /**
     * @template T of object
     * @param class-string<T> $type
     * @return T|Response
     */
    public function queryTypedOrResponse($type): object
    {
        $result = Typed::object(type: $type, input: $this->query());
        if ($result instanceof ErrorCollection) {
            return Response::badRequest($result);
        }
        return $result;
    }

    /**
     * @return array<string,mixed>
     */
    public function body(): array
    {
        if ($this->resolvedBody === null) {
            $parsed = $this->getParsedBody();
            if (is_array($parsed) && $parsed) {
                /** @var array<string,mixed> $parsed */
                $this->resolvedBody = $parsed;
            } else {
                $body = $this->getBody()->getContents();
                $data = json_decode($body, true);
                $this->resolvedBody = is_array($data) ? $data : [];
            }
        }

        return $this->resolvedBody;
    }

    /**
     * @template T of object
     * @param class-string<T> $type
     * @return T|ErrorCollection
     */
    public function bodyTypedOrErrors(string $type): object
    {
        return Typed::object(type: $type, input: $this->body());
    }

    /**
     * @template T of object
     * @param class-string<T> $type
     * @return T|Response
     */
    public function bodyTypedOrResponse($type): object
    {
        $result = Typed::object(type: $type, input: $this->body());
        if ($result instanceof ErrorCollection) {
            return Response::badRequest($result);
        }
        return $result;
    }

    public function post(): bool
    {
        return $this->getMethod() === 'POST';
    }

    public function bearer(): ?string
    {
        $header = $this->getHeaderLine('Authorization');
        if (!str_starts_with($header, 'Bearer ')) {
            return null;
        }

        $bearer = substr($header, 7);

        if ($bearer === '') {
            return null;
        }

        return substr($header, 7);
    }

    public function upload(): ?UploadedFile
    {
        $uploadedFiles = $this->getUploadedFiles();

        $iterator = new RecursiveIteratorIterator(
            new RecursiveArrayIterator($uploadedFiles)
        );

        foreach ($iterator as $file) {
            if ($file instanceof UploadedFile) {
                return $file;
            }
        }

        return null;
    }

    /**
     * @param array<string,mixed> $query
     */
    public function alterUri(array $query): string
    {
        $uri = $this->getUri();
        $qs = $uri->getQuery();
        $qa = [];

        if ($qs !== '') {
            parse_str($qs, $qa);
        }

        $qa = array_merge($qa, $query);
        $qs = http_build_query($qa);

        return (string) $uri->withQuery($qs);
    }

    public function getPathAndQuery(): string
    {
        $uri = $this->getUri();
        $path = $uri->getPath();
        $query = $uri->getQuery();

        return $query === '' ? $path : $path . '?' . $query;
    }

    public function csrf(): string
    {
        $body = $this->body();
        $token = $body['_csrf'] ?? $this->getHeaderLine('X-CSRF-Token');

        if (is_string($token)) {
            return $token;
        }

        return '';
    }
}
