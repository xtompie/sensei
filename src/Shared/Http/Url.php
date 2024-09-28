<?php

declare(strict_types=1);

namespace App\Shared\Http;

use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;

final class Url
{
    public function __construct(
        private Routes $routes,
        private ?UrlGenerator $urlGenerator = null
    ) {
    }

    /**
     * @param class-string $controller
     * @param array<string, mixed> $parameters
     * @return string
     */
    public function __invoke(string $controller, array $parameters = []): string
    {
        return $this->generator()->generate($controller, $parameters, UrlGenerator::ABSOLUTE_PATH);
    }

    private function generator(): UrlGenerator
    {
        if ($this->urlGenerator === null) {
            $this->urlGenerator = new UrlGenerator(
                routes: $this->routes->routes(),
                context: new RequestContext(),
            );
        }
        return $this->urlGenerator;
    }
}
