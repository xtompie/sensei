<?php

declare(strict_types=1);

namespace App\Shared\Http;

use Xtompie\Container\Container;
use Xtompie\Container\Provider;
use Laminas\Diactoros\ServerRequest;
use Xtompie\Result\ErrorCollection;
use Xtompie\Typed\Typed;

final class Request extends ServerRequest implements Provider
{
    private ?array $resolvedBody = null;

    public static function provide(string $abstract, Container $container): object
    {
        return ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );
    }

    /**
     * @return array<string, mixed>
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
    public function queryTyped($type): object
    {
        $result = Typed::object(type: $type, input: $this->query());
        if ($result instanceof ErrorCollection) {
            return Response::badRequest($result);
        }
        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    public function body(): array
    {
        if ($this->resolvedBody === null) {
            $parsed = $this->getParsedBody();
            if (is_array($parsed) && $parsed) {
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
     * @return T|Response
     */
    public function bodyTyped($type): object
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
}
