<?php

declare(strict_types=1);

namespace App\Shared\Tpl;

use App\Shared\Container\Container;
use App\Shared\Kernel\AppDir;
use App\Shared\Kernel\Debug;
use Xtompie\Tpl\Tpl as BaseTpl;

final class Tpl extends BaseTpl
{
    /**
     * @param array<string, bool> $import
     */
    public function __construct(
        private AppDir $appDir,
        private Debug $debug,
        private ?string $templatePath = null,
        private array $import = [],
    ) {
    }

    protected function templatePathPrefix(): string
    {
        if (!$this->templatePath) {
            $this->templatePath = $this->appDir->__invoke();
        }
        return $this->templatePath;
    }

    /**
     * @param string $template
     * @param array<string, mixed> $data
     * @return string
     */
    protected function render(string $template, array $data = []): string
    {
        if ($this->debug->__invoke()) {
            return "\n<!-- #template: $template  -->\n"
                . parent::render($template, $data)
                . "\n<!-- /template: $template -->\n"
            ;
        }

        return parent::render($template, $data);
    }

    /**
     * @template T of object
     * @param class-string<T> $service
     * @return T
     */
    protected function service(string $service): object
    {
        return Container::container()->get($service);
    }

    protected function import(string $template): string
    {
        if (isset($this->import[$template])) {
            return '';
        }
        $this->import[$template] = true;

        return $this->render($template);
    }

    protected function sentry(string $sid): bool
    {
        return true; // @TEST
        return $this->service(\App\Sentry\Application\Service\Sentry\Sentry::class)->__invoke($sid);
    }
}
