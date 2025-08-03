<?php

declare(strict_types=1);

namespace App\Mcp\Application\Service\Server;

use App\Mcp\Application\Model\Specification;
use App\Shared\Http\Response;
use App\Shared\Http\Request;

final class HandleInitialize
{
    private const PROTOCOL_VERSION = '2025-06-18';

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
        $requestedVersion = $params['protocolVersion'];
        if (!is_string($requestedVersion)) {
            return $this->responseFactory->invalidParams('protocolVersion must be a string');
        }

        if (!$requestedVersion) {
            return $this->responseFactory->invalidParams('Missing protocolVersion');
        }

        if ($requestedVersion !== self::PROTOCOL_VERSION) {
            return $this->responseFactory->unsupportedProtocolVersion(
                $requestedVersion,
                [self::PROTOCOL_VERSION]
            );
        }

        return $this->responseFactory->success([
            'protocolVersion' => self::PROTOCOL_VERSION,
            'capabilities' => [
                'tools' => [
                    'listChanged' => false,
                ],
            ],
            'serverInfo' => [
                'name' => $specification->serverName(),
                'version' => $specification->serverVersion(),
            ],
        ]);
    }
}
