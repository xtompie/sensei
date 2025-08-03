<?php

declare(strict_types=1);

namespace App\Mcp\Application\Service\Server;

use App\Shared\Http\Response;
use App\Mcp\Application\Model\Specification;
use App\Mcp\Application\Model\Type;
use App\Mcp\Application\Model\StringType;
use App\Mcp\Application\Model\NumberType;
use App\Mcp\Application\Model\BooleanType;
use App\Mcp\Application\Model\ArrayType;
use App\Mcp\Application\Model\ObjectType;
use InvalidArgumentException;

final class HandleToolsList
{
    public function __construct(
        private ResponseFactory $responseFactory,
    ) {
    }

    public function __invoke(Specification $specification): Response
    {
        $tools = [];

        foreach ($specification->tools() as $tool) {
            $inputSchema = [
                'type' => 'object',
                'properties' => [],
                'required' => [],
            ];

            foreach ($tool->parameters() as $parameter) {
                $inputSchema['properties'][$parameter->name] = $this->typeToJsonSchema($parameter->type);
                if ($parameter->required) {
                    $inputSchema['required'][] = $parameter->name;
                }
            }

            $toolDefinition = [
                'name' => $tool->name(),
                'description' => $tool->description(),
                'inputSchema' => $inputSchema,
            ];

            if ($tool->title() !== null) {
                $toolDefinition['title'] = $tool->title();
            }

            if ($tool->outputSchema() !== null) {
                $toolDefinition['outputSchema'] = $this->typeToJsonSchema($tool->outputSchema());
            }

            if ($tool->annotations() !== null) {
                $toolDefinition['annotations'] = $tool->annotations();
            }

            $tools[] = $toolDefinition;
        }

        return $this->responseFactory->success(['tools' => $tools]);
    }

    /**
     * @return array<string,mixed>
     */
    private function typeToJsonSchema(Type $type): array
    {
        if ($type instanceof StringType) {
            return ['type' => 'string'];
        }

        if ($type instanceof NumberType) {
            return ['type' => 'number'];
        }

        if ($type instanceof BooleanType) {
            return ['type' => 'boolean'];
        }

        if ($type instanceof ArrayType) {
            return [
                'type' => 'array',
                'items' => $this->typeToJsonSchema($type->items),
            ];
        }

        if ($type instanceof ObjectType) {
            $schema = [
                'type' => 'object',
                'properties' => [],
                'required' => [],
            ];

            foreach ($type->properties as $name => $propertyType) {
                $schema['properties'][$name] = $this->typeToJsonSchema($propertyType);
            }

            return $schema;
        }

        throw new InvalidArgumentException('Unsupported type for JSON schema conversion: ' . get_class($type));
    }
}
