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

final class HandleToolsList
{
    public function __invoke(Specification $specification): Response
    {
        $tools = [];

        foreach ($specification->tools as $tool) {
            $toolData = [
                'name' => $tool->id,
                'description' => $tool->description,
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [],
                    'required' => []
                ]
            ];

            foreach ($tool->methods as $method) {
                $methodSchema = [
                    'type' => 'object',
                    'properties' => [],
                    'required' => []
                ];

                foreach ($method->parameters as $parameter) {
                    $methodSchema['properties'][$parameter->name] = $this->typeToJsonSchema($parameter->type);
                    if ($parameter->required) {
                        $methodSchema['required'][] = $parameter->name;
                    }
                }

                $toolData['inputSchema']['properties'][$method->name] = $methodSchema;
            }

            $tools[] = $toolData;
        }

        return Response::json(['tools' => $tools]);
    }

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
                'items' => $this->typeToJsonSchema($type->items)
            ];
        }

        if ($type instanceof ObjectType) {
            $schema = [
                'type' => 'object',
                'properties' => [],
                'required' => []
            ];

            foreach ($type->properties as $name => $propertyType) {
                $schema['properties'][$name] = $this->typeToJsonSchema($propertyType);
            }

            return $schema;
        }

        return ['type' => 'string'];
    }
}
