<?php

declare(strict_types=1);

namespace App\Shared\Http;

use App\Shared\Container\Container;
use App\Shared\Kernel\Debug;
use App\Shared\Profiler\ProfileHttpRouter;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\CompiledUrlMatcher;
use Symfony\Component\Routing\RequestContext;

final class Router
{
    public function __construct(
        private Debug $debug,
        private Routes $routes,
    ) {
    }

    public function __invoke(): RouterResult|Response
    {
        $result = $this->match();

        if ($this->debug->__invoke()) {
            Container::container()->get(ProfileHttpRouter::class)->__invoke($result);
        }

        return $result;
    }

    private function match(): RouterResult|Response
    {
        $container = Container::container();
        $request = $container->get(Request::class);
        try {
            $result = $this->matched(
                $this
                    ->matcher($request)
                    ->match(pathinfo: $request->getUri()->getPath())
            );
        } catch (ResourceNotFoundException) {
            return Response::notFound();
        } catch (MethodNotAllowedException) {
            return Response::methodNotAllowed();
        }

        foreach ($result->parameters as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }
        $container->instance(Request::class, $request);

        return $result;
    }

    private function matcher(Request $request): CompiledUrlMatcher
    {
        return new CompiledUrlMatcher(
            compiledRoutes: $this->routes->compiled(),
            context: new RequestContext(
                baseUrl: $request->getUri()->getPath(),
                method: $request->getMethod(),
                host: $request->getUri()->getHost(),
            ),
        );
    }

    /**
     * @param array<string,mixed> $match
     */
    private function matched(array $match): RouterResult
    {
        $controller = $match['_controller'];
        unset($match['_controller'], $match['_route']);
        if (!is_string($controller) || !class_exists($controller)) {
            throw new \InvalidArgumentException('Controller must be a valid class-string.');
        }

        return new RouterResult(
            controller: $controller,
            parameters: $match,
        );
    }
}
