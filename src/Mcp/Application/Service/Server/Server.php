<?php

declare(strict_types=1);

namespace App\Mcp\Application\Service\Server;

use App\Shared\Http\Request;
use App\Shared\Http\Response;
use App\Mcp\Application\Model\Specification;

final class Server
{
    public function __construct(
        private HandleInitialize $handleInitialize,
        private HandleToolsList $handleToolsList,
        private HandleToolCall $handleToolCall,
        private ResponseFactory $responseFactory,
    ) {
    }

    public function __invoke(Request $request, Specification $specification): Response
    {
        $data = $request->body();

        if (!isset($data['jsonrpc']) || $data['jsonrpc'] !== '2.0') {
            return $this->responseFactory->invalidRequest();
        }

        if (!isset($data['method'])) {
            return $this->responseFactory->invalidRequest('Missing method field');
        }

        $method = $data['method'];

        return match ($method) {
            'initialize' => $this->handleInitialize->__invoke(request: $request, specification: $specification),
            'tools/list' => $this->handleToolsList->__invoke(specification: $specification),
            'tools/call' => $this->handleToolCall->__invoke(request: $request, specification: $specification),
            'notifications/initialized' => $this->responseFactory->notificationInitialized(),
            default => $this->responseFactory->methodNotFound(),
        };
    }
}
