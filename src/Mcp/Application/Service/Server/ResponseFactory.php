<?php

declare(strict_types=1);

namespace App\Mcp\Application\Service\Server;

use App\Shared\Http\Request;
use App\Shared\Http\Response;

final class ResponseFactory
{
    public function __construct(
        private Request $request
    ) {
    }

    private function id(): string|int|null
    {
        $data = $this->request->body();
        $id = $data['id'] ?? null;

        if (is_string($id) || is_int($id) || $id === null) {
            return $id;
        }

        return null;
    }

    public function success(mixed $result): Response
    {
        return Response::json([
            'jsonrpc' => '2.0',
            'id' => $this->id(),
            'result' => $result,
        ]);
    }

    public function error(int $code, string $message, mixed $data = null): Response
    {
        $error = [
            'jsonrpc' => '2.0',
            'id' => $this->id(),
            'error' => [
                'code' => $code,
                'message' => $message,
            ],
        ];

        if ($data !== null) {
            $error['error']['data'] = $data;
        }

        return Response::json($error);
    }

    public function invalidRequest(?string $data = null): Response
    {
        return $this->error(-32600, 'Invalid Request', $data);
    }

    public function methodNotFound(): Response
    {
        return $this->error(-32601, 'Method not found');
    }

    public function invalidParams(string $data): Response
    {
        return $this->error(-32602, 'Invalid params', $data);
    }

    /**
     * MCP-specific: Unsupported protocol version
     *
     * @param array<string> $supported
     */
    public function unsupportedProtocolVersion(string $requested, array $supported): Response
    {
        return $this->error(-32602, 'Unsupported protocol version', [
            'supported' => $supported,
            'requested' => $requested,
        ]);
    }

    public function unknownTool(string $toolName): Response
    {
        return $this->error(-32602, 'Unknown tool', "Tool not found: $toolName");
    }

    /**
     * @param array<array<string,mixed>> $content
     */
    public function toolCall(array $content, bool $isError = false, mixed $structuredContent = null): Response
    {
        $result = [
            'content' => $content,
            'isError' => $isError,
        ];

        if ($structuredContent !== null) {
            $result['structuredContent'] = $structuredContent;
        }

        return $this->success($result);
    }

    /**
     * MCP-specific: initialize response
     *
     * @param array<string,mixed> $capabilities
     * @param array<string,mixed> $serverInfo
     */
    public function initialize(string $protocolVersion, array $capabilities, array $serverInfo): Response
    {
        return $this->success([
            'protocolVersion' => $protocolVersion,
            'capabilities' => $capabilities,
            'serverInfo' => $serverInfo,
        ]);
    }

    public function notificationInitialized(): Response
    {
        return Response::json([], 204);
    }
}
