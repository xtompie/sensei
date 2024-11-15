<?php

declare(strict_types=1);

namespace App\Shared\Pao;

use Exception;
use Ramsey\Uuid\Uuid;

class OptimisticLockHook
{
    public function __construct(
        protected string $field = 'version',
        protected string $strategy = 'increment',
    ) {
    }

    /**
     * @param array<string,mixed>|null $present
     * @param array<string,mixed> $future
     * @return array<string,mixed>
     */
    public function __invoke(?array $present, array $future): array
    {
        if ($present !== null && $present[$this->field] !== $future[$this->field]) {
            if (!isset($future[':table'])) {
                throw new Exception('Missing table name');
            }
            if (!is_string($future[':table'])) {
                throw new Exception('Invalid table name');
            }
            if (!isset($future['id'])) {
                throw new Exception('Missing id');
            }
            if (!is_string($future['id'])) {
                throw new Exception('Invalid id');
            }
            if (!is_string($present[$this->field])) {
                throw new Exception('Missing version');
            }
            if (!is_string($future[$this->field])) {
                throw new Exception('Missing version');
            }
            throw OptimisticLockException::versionMismatch(
                table: $future[':table'],
                id: $future['id'],
                expected: $present[$this->field],
                actual: $future[$this->field],
            );
        }

        $future[$this->field] = $this->createNewVersion($present);

        return $future;
    }

    /**
     * @param array<string,mixed>|null $present
     */
    public function createNewVersion(?array $present): string
    {
        if ($this->strategy === 'increment') {
            if (!$present) {
                return '1';
            }
            if (!is_string($present[$this->field])) {
                throw new Exception();
            }
            $version = intval($present[$this->field]);
            return (string) ($version + 1);
        }
        if ($this->strategy === 'state') {
            return Uuid::uuid4()->__toString();
        }
        throw new Exception();
    }
}
