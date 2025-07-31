<?php

declare(strict_types=1);

namespace App\Mcp\Application\Service\Server;

use App\Shared\Http\Request;
use App\Shared\Http\Response;
use App\Mcp\Application\Model\Specification;

final class Server
{
    public function __construct(
        private HandleCapabilities $handleCapabilities,
        private HandleToolsList $handleToolsList,
        private HandleToolCall $handleToolCall
    ) {
    }

    public function __invoke(Request $request, Specification $specification, string $prefix = '/mcp'): Response
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        if (!str_starts_with($path, $prefix)) {
            return Response::json(['error' => 'Invalid path'], 404);
        }

        $relativePath = substr($path, strlen($prefix));
        if ($relativePath === '') {
            $relativePath = '/';
        }

        return match ($relativePath) {
            '/capabilities' => ($this->handleCapabilities)($specification),
            '/tools/list' => ($this->handleToolsList)($specification),
            '/tools/call' => ($this->handleToolCall)($request, $specification),
            default => Response::json(['error' => 'Not Found'], 404),
        };
    }
}
