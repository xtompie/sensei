<?php

declare(strict_types=1);

namespace App\Shared\Http;

use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;

final class Url
{
    public function __construct(
        private Routes $routes,
        private UrlParameterContext $urlParameterContext,
        private ?UrlGenerator $urlGenerator = null,
    ) {
    }

    /**
     * @param class-string $controller
     * @param array<string,mixed> $parameters
     * @return string
     */
    public function __invoke(
        string $controller,
        array $parameters = [],
        ?UrlReference $reference = null,
        bool $useParamContext = true,
    ): string {
        $referenceType = match ($reference) {
            UrlReference::absoluteUrl() => UrlGenerator::ABSOLUTE_URL,
            UrlReference::absolutePath() => UrlGenerator::ABSOLUTE_PATH,
            UrlReference::relativePath() => UrlGenerator::RELATIVE_PATH,
            UrlReference::newtworkPath() => UrlGenerator::NETWORK_PATH,
            default => UrlGenerator::ABSOLUTE_PATH,
        };

        $parameters = $useParamContext ? array_merge($this->urlParameterContext->context(), $parameters) : $parameters;

        return $this->generator()->generate(
            name: $controller,
            parameters: $parameters,
            referenceType: $referenceType,
        );
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
