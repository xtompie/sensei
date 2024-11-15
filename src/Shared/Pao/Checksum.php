<?php

declare(strict_types=1);

namespace App\Shared\Pao;

use Xtompie\Dao\Dao;

class Checksum
{
    public function __construct(
        protected Dao $dao,
    ) {
    }

    /**
     * @param array<string,mixed> $projection
     */
    public function validate(array $projection, string $field = 'checksum'): bool
    {
        if (!is_string($projection[':table'])) {
            throw new \InvalidArgumentException('Invalid table name');
        }
        return $this->dao->exists($projection[':table'], ['id' => $projection['id'], $field => $projection[$field]]);
    }

    /**
     * @param array<string,mixed> $projection
     * @return array<string,mixed>
     */
    public function generate(array $projection, string $field = 'checksum'): array
    {
        unset($projection[$field]);
        $projection[$field] = sha1(serialize($projection));
        return $projection;
    }
}
