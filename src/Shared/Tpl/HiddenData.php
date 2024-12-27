<?php

declare(strict_types=1);

namespace App\Shared\Tpl;

class HiddenData
{
    public function __invoke(array $data): string
    {
        return $this->node($data, []);
    }

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
                $result .= $this->node($value, array_merge($path, [$key]));
            }
            return $result;
        }
    }

    protected function input(string $name, string $value): string
    {
        return '<input type="hidden" name="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($value) . '" />';
    }

    protected function name(array $parts): string
    {
        $first = array_shift($parts);
        return $first . ($parts ? '[' . implode('][', $parts) . ']' : '');
    }
}
