<?php

declare(strict_types=1);

namespace App\Shared\Tpl;

class HiddenData
{
    /**
     * @param array<string,mixed> $data
     */
    public function __invoke(array $data): string
    {
        return $this->node($data, []);
    }

    /**
     * @param array<string,mixed>|string|null $data
     * @param array<int,string> $path
     */
    protected function node(mixed $data, array $path): string
    {
        if ($data === null) {
            return '';
        }
        if (is_scalar($data)) {
            return $this->input($this->name($path), (string) $data);
        } else {
            $result = '';
            foreach ($data as $key => $value) {
                /** @var array<string,mixed>|string|null $value */
                $result .= $this->node($value, array_merge($path, [$key]));
            }
            return $result;
        }
    }

    protected function input(string $name, string $value): string
    {
        return '<input type="hidden" name="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($value) . '" />';
    }

    /**
     * @param array<int,string> $parts
     */
    protected function name(array $parts): string
    {
        $first = array_shift($parts);
        return $first . ($parts ? '[' . implode('][', $parts) . ']' : '');
    }
}
