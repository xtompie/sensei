<?php

declare(strict_types=1);

namespace App\Backend\Resource\Admin;

use App\Backend\System\Resource\AbstractIndexController;

class IndexController extends AbstractIndexController
{
    /**
     * @return array<string>
     */
    protected function filters(): array
    {
        return [
            'id:match',
            'email',
            'role',
        ];
    }

    protected function orders(): array
    {
        return [
            'id',
            'email',
            'role',
        ];
    }
}
