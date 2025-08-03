<?php

declare(strict_types=1);

namespace App\Mcp\Application\Model;

final class Specification
{
    /**
     * @param Tool[] $tools
     */
    public function __construct(
        private array $tools,
        private string $serverName = 'MCP Server',
        private string $serverVersion = '1.0.0'
    ) {
    }

    public function findToolByName(string $name): ?Tool
    {
        foreach ($this->tools as $tool) {
            if ($tool->name() === $name) {
                return $tool;
            }
        }
        return null;
    }

    /**
     * @return Tool[]
     */
    public function tools(): array
    {
        return $this->tools;
    }

    public function serverName(): string
    {
        return $this->serverName;
    }

    public function serverVersion(): string
    {
        return $this->serverVersion;
    }
}
