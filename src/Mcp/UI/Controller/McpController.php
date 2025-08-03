<?php

declare(strict_types=1);

namespace App\Mcp\UI\Controller;

use App\Shared\Http\Controller;
use App\Shared\Http\Request;
use App\Shared\Http\Response;
use App\Shared\Http\Route\Path;
use App\Shared\Http\Route\GET;
use App\Shared\Http\Route\POST;
use App\Mcp\Application\Service\Server\Server;
use App\Mcp\Application\Model\Specification;
use App\Mcp\Application\Model\Tool;
use App\Mcp\Application\Model\Parameter;
use App\Mcp\Application\Model\NumberType;

#[Path('mcp/*'), GET, POST]
class McpController implements Controller
{
    public function __construct(
        private Server $server
    ) {
    }

    public function __invoke(Request $request): Response
    {
        return $this->server->__invoke($request, $this->specification());
    }

    private function specification(): Specification
    {
        return new Specification(tools: [
            new Tool(
                name: 'calculator.add',
                description: 'Add two numbers together',
                handler: $this->add(...),
                parameters: [
                    new Parameter(name: 'a', type: new NumberType(), required: true),
                    new Parameter(name: 'b', type: new NumberType(), required: true),
                ],
                return: new NumberType(),
                title: 'Calculator Add'
            ),
        ]);
    }

    public function add(float $a, float $b): float
    {
        return $a + $b;
    }
}
