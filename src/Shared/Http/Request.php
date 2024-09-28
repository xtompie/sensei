<?php

declare(strict_types=1);

namespace App\Shared\Http;

use Xtompie\Container\Container;
use Xtompie\Container\Provider;
use Laminas\Diactoros\ServerRequest;
use Xtompie\Result\ErrorCollection;
use Xtompie\Typed\Typed;

class Request extends ServerRequest implements Provider
{
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
     * @return array<string, mixed>|object|null
     */
    public function body(): null|array|object
    {
        return $this->getParsedBody();
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
