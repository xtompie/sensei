<?php

declare(strict_types=1);

namespace App\Mcp\Application\Service\Server;

use App\Shared\Http\Response;
use App\Mcp\Application\Model\Specification;

final class HandleCapabilities
{
    private const string PROTOCOL_VERSION = '2024-11-05';

    public function __invoke(Specification $specification): Response
    {
        $capabilities = [
            'protocol_version' => self::PROTOCOL_VERSION,
            'capabilities' => [
                'tools' => [
                    'listChanged' => $specification->toolsListChanged
                ]
            ],
            'server_info' => [
                'name' => $specification->serverName,
                'version' => $specification->serverVersion
            ]
        ];

        return Response::json($capabilities);
    }
}
