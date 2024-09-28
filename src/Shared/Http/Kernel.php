<?php

declare(strict_types=1);

namespace App\Shared\Http;

use App\Shared\Aop\Advice;
use App\Shared\Aop\Aop;
use App\Shared\Container\Container;
use App\Shared\Http\Aop\CsrfVerify;
use App\Shared\Http\Contract\RequestBody;
use App\Shared\Http\Contract\RequestQuery;
use App\Shared\Kernel\Debug;
use App\Shared\Profiler\HttpReporter;
use App\Shared\Profiler\ProfileHttpRequest;
use App\Shared\Profiler\ProfileHttpResponse;
use App\Shared\Profiler\ProfileHttpSession;
use App\Shared\Profiler\ProfileStart;
use App\Shared\Profiler\ProfileStop;
use Closure;
use Exception;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;

final class Kernel
{
    public function __construct(
        private Router $router,
        private Debug $debug,
        private Request $request,
    ) {
    }

    /**
     * @return array<Advice>
     */
    private function prepend(): array
    {
        return [
        ];
    }

    /**
     * @return array<Advice>
     */
    private function append(): array
    {
        return [
            new CsrfVerify(),
        ];
    }

    private function response(mixed $output): Response
    {
        if ($output instanceof Response) {
            return $output;
        }

        if (is_string($output)) {
            return Response::new(body: $output);
        }

        throw new Exception('Invalid response');
    }

    public function __invoke(): void
    {
        ob_start();
        $this->debugStart();
        $container = Container::container();

        $match = $this->router->__invoke();
        if ($match instanceof Response) {
            $this->emmit($match);
            return;
        }

        $controller = $container->get($match->controller);
        if (!is_callable($controller)) {
            throw new Exception('Invalid controller');
        }

        $args = $container->callArgs(
            callback: [$controller, '__invoke'],
            values: $match->parameters,
            arg: fn (ReflectionParameter $parameter) => $this->arg($parameter),
        );
        $response = Aop::aop(
            method: "$match->controller::__invoke",
            args: $args,
            main: function (...$args) use ($controller) {
                foreach ($args as $index => $arg) {
                    if (!$arg instanceof Closure) {
                        continue;
                    }
                    $args[$index] = $arg();
                    if ($args[$index] instanceof Response) {
                        return $args[$index];
                    }
                }
                return $this->response($controller(...$args));
            },
            prepend: $this->prepend(),
            append: $this->append(),
        );

        if (!$response instanceof Response) {
            throw new Exception('Invalid response');
        }

        $this->emmit($response);
    }

    private function emmit(Response $response): void
    {
        if (ob_get_length()) {
            if ($this->debug->__invoke()) {
                return;
            }
            ob_end_clean();
            throw new Exception('Headers already sent');
        }

        $response = $this->debugStop($response);
        (new SapiEmitter())->emit($response);
    }

    private function arg(ReflectionParameter $parameter): mixed
    {
        $type = $parameter->getType();
        $abstract = $type instanceof ReflectionNamedType ? $type->getName() : null;
        $class = is_string($abstract) && class_exists($abstract) ? new ReflectionClass($abstract) : null;
        if ($class === null) {
            return null;
        }
        if ($class->implementsInterface(RequestBody::class)) {
            return fn () => $this->request->bodyTyped($abstract);
        }
        if ($class->implementsInterface(RequestQuery::class)) {
            return fn () => $this->request->queryTyped($abstract);
        }
        return null;
    }

    private function debugStart(): void
    {
        if (!$this->debug->__invoke()) {
            return;
        }

        Container::container()->get(ProfileStart::class)->__invoke();
        Container::container()->get(ProfileHttpRequest::class)->__invoke();
        Container::container()->get(ProfileHttpSession::class)->start();
    }

    private function debugStop(Response $response): Response
    {
        if (!$this->debug->__invoke()) {
            return $response;
        }

        Container::container()->get(ProfileHttpSession::class)->stop();
        Container::container()->get(ProfileHttpResponse::class)->__invoke($response);
        Container::container()->get(ProfileStop::class)->__invoke();
        $response = Container::container()->get(HttpReporter::class)->__invoke($response);

        if (!$response instanceof Response) {
            throw new Exception('Invalid response');
        }

        return $response;
    }
}
