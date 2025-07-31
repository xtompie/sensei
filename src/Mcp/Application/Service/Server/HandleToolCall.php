<?php

declare(strict_types=1);

namespace App\Mcp\Application\Service\Server;

use App\Shared\Http\Request;
use App\Shared\Http\Response;
use App\Mcp\Application\Model\Specification;

final class HandleToolCall
{
    private function error(string $message, int $code = 400): Response
    {
        return Response::json(['error' => $message], $code);
    }

    private function ok(mixed $result, mixed $callId = null): Response
    {
        $response = [
            'content' => [
                [
                    'type' => 'text',
                    'text' => json_encode($result)
                ]
            ]
        ];

        if ($callId !== null) {
            $response['id'] = $callId;
        }

        return Response::json($response);
    }

    public function __invoke(Request $request, Specification $specification): Response
    {
        $data = $request->body();

        if (!$data || !isset($data['name']) || !isset($data['arguments'])) {
            return $this->error('Invalid request');
        }

        $callId = $data['id'] ?? null;

        if (!is_string($data['name'])) {
            return $this->error('Tool name must be string');
        }

        if ($data['arguments'] === null) {
            $arguments = [];
        } elseif (!is_array($data['arguments'])) {
            return $this->error('Arguments must be array');
        } else {
            $arguments = $data['arguments'];
        }

        $toolName = $data['name'];

        $tool = $specification->findToolById($toolName);
        if (!$tool) {
            return $this->error('Tool not found', 404);
        }

        if (empty($arguments)) {
            return $this->error('No method specified');
        }

        if (count($arguments) > 1) {
            return $this->error('Only one method can be called at a time');
        }

        $methodName = array_key_first($arguments);
        if (!is_string($methodName)) {
            return $this->error('Method name must be string');
        }

        $methodArgs = $arguments[$methodName];
        if (!is_array($methodArgs)) {
            return $this->error('Method arguments must be array');
        }

        $method = $tool->findMethodByName($methodName);
        if (!$method) {
            return $this->error("Method '{$methodName}' not found", 404);
        }

        try {
            $result = $method->call($methodArgs);
            return $this->ok($result, $callId);
        } catch (\Throwable $e) {
            return $this->error('Method execution failed: ' . $e->getMessage(), 500);
        }
    }
}
