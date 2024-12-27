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
        private Request $request,
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
            UrlReference::ABSOLUTE_URL => UrlGenerator::ABSOLUTE_URL,
            UrlReference::ABSOLUTE_PATH => UrlGenerator::ABSOLUTE_PATH,
            UrlReference::RELATIVE_PATH => UrlGenerator::RELATIVE_PATH,
            UrlReference::NETWORK_PATH => UrlGenerator::NETWORK_PATH,
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
                context: new RequestContext(
                    host: $this->request->getUri()->getHost(),
                    httpPort: $this->request->getUri()->getPort() ?: 80,
                ),
            );
        }
        return $this->urlGenerator;
    }
}
