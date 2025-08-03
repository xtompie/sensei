<?php

declare(strict_types=1);

namespace App\Mcp\Application\Service\Server;

use App\Shared\Http\Response;
use App\Mcp\Application\Model\Specification;
use App\Shared\Http\Request;

final class HandleToolCall
{
    public function __construct(
        private ResponseFactory $responseFactory,
    ) {
    }

    public function __invoke(Request $request, Specification $specification): Response
    {
        $body = $request->body();
        $params = $body['params'] ?? [];
        if (!is_array($params)) {
            return $this->responseFactory->invalidParams('Params must be an array');
        }
        $name = is_string($params['name'] ?? null) ? $params['name'] : '';
        $arguments = is_array($params['arguments'] ?? null) ? $params['arguments'] : [];

        if (!$name) {
            return $this->responseFactory->invalidParams('Missing tool name');
        }

        $tool = $specification->findToolByName($name);
        if (!$tool) {
            return $this->responseFactory->unknownTool($name);
        }

        try {
            $result = $tool->call($arguments);

            return $this->responseFactory->toolCall(
                content: [
                    [
                        'type' => 'text',
                        'text' => is_string($result) ? $result : json_encode($result),
                    ],
                ],
                isError: false,
                structuredContent:  ($tool->outputSchema() !== null) ? $result : null,
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->toolCall(
                content: [
                    [
                        'type' => 'text',
                        'text' => 'Error: ' . $e->getMessage(),
                    ],
                ],
                isError: true
            );
        }
    }
}
