<?php

declare(strict_types=1);

namespace App\Shared\Profiler;

use App\Shared\Http\Response;
use App\Shared\Kernel\AppDir;

final class HttpReporter
{
    public function __construct(
        private Data $data,
        private AppDir $appDir,
    ) {
    }

    public function __invoke(Response $response): Response
    {
        $this->toolsProfilerHttpLog();
        $response = $this->headerXDebug($response);
        // $response = $this->htmlScriptConsoleLog($response);
        return $response;
    }

    private function headerXDebug(Response $response): Response
    {
        foreach ($this->data->get() as $k => $v) {
            /** @var array{type:string,data:array<mixed>} $v */
            $data = json_encode($v['data']);
            if ($data === false) {
                continue;
            }
            $response = $response->withHeader(
                'X-Debug-' . str_pad((string) $k, 4, '0', STR_PAD_LEFT),
                $v['type'] . ' | ' . $data
            );
        }

        return $response;
    }

    private function toolsProfilerHttpLog(): void
    {
        $out = '';
        foreach ($this->data->get() as $v) {
            /** @var array{type:string,data:array<mixed>} $v */
            $out .= $v['type'] . ' | ' . json_encode($v['data']) . "\n";
        }

        file_put_contents($this->appDir->get() . '/tools/profiler/http.log', $out);
    }

    private function htmlScriptConsoleLog(Response $response): Response
    {
        if ($this->isHtml($response)) {
            $body = $response->getBody();
            $content = (string) $body;
            $body->rewind();
            foreach ($this->data->get() as $i) {
                $content .= '<script>console.log(' . json_encode($i['type']) . ' + " | " + JSON.stringify(' . json_encode($i['data']) . '));</script>';
            }
            $body->write($content);
        }

        return $response;
    }

    private function isHtml(Response $response): bool
    {
        $contentType = $response->getHeader('Content-Type');
        return !empty($contentType) && str_starts_with($contentType[0], 'text/html');
    }
}
