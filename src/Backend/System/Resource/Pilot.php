<?php

declare(strict_types=1);

namespace App\Backend\System\Resource;

use App\Backend\System\Validation\Validation;

interface Pilot
{
    /**
     * Name of the resource. This is used to identify the resource in the backend.
     * Same as folder name in src/Backend/Resource. E.g. 'article'.
     */
    public static function resource(): string;

    /**
     * Returns array of links.
     *
     * @param array<string, mixed>|null $entity
     * @return array<int, mixed>
     */
    public function breadcrumb(string $action, ?array $entity = null): array;

    /**
     * Link an array<string, string> with keys: resource, action, sentry, title, url.
     *
     * @param array<string, mixed>|null $entity
     * @return array<string, mixed>
     */
    public function link(string $action, ?array $entity = null): array;

    /**
     * Returns array of links.
     *
     * @param array<string, mixed>|null $entity
     * @return array<int, mixed>
     */
    public function more(string $action, ?array $entity = null): array;

    /**
     * Same as static::resource() but not static.
     */
    public function name(): string;

    /**
     * Whitle list of entity keys in selection.
     * When using selection mechanism, only these keys will be returned.
     *
     * @return array<string>
     */
    public function selection(): array;

    /**
     * Title of the entity.
     * @param array<string, mixed>|null $entity
     */
    public function title(string $action, ?array $entity = null): ?string;

    /**
     * Url
     *
     * @param array<string, mixed>|null $entity
     * @param array<string, mixed> $params
     */
    public function url(string $action, ?array $entity = null, array $params = []): string;

    /**
     * White list of entity keys in write operations.
     * WHen using insert or update, only these keys from $_POST will be used.
     *
     * @return array<string, mixed>
     */
    public function values(string $action): array;

    /**
     * Validation rules.
     *
     * @param array<string, mixed>|null $entity
     */
    public function validation(Validation $validation, string $action, ?array $entity): Validation;
}
