<?php

declare(strict_types=1);

namespace App\Backend\System\Menu;

class Menu
{
    /**
     * @return array<int, mixed>
     */
    public function __invoke(): array
    {
        return [
            [
                'name' => 'Admin',
                'url' => '/admin',
            ],
            [
                'name' => 'User',
                'url' => '/user',
            ],
        ];
    }
}
