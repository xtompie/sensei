<?php

declare(strict_types=1);

namespace App\Mcp\Application\Model;

final class Specification
{
    /**
     * @param Tool[] $tools
     */
    public function __construct(
        public array $tools,
        public bool $toolsListChanged = false,
        public string $serverName = 'Sensei MCP Server',
        public string $serverVersion = '1.0.0'
    ) {
    }

    public function findToolById(string $id): ?Tool
    {
        foreach ($this->tools as $tool) {
            if ($tool->id === $id) {
                return $tool;
            }
        }
        return null;
    }
}
