<?php

declare(strict_types=1);

namespace App\Backend\Resource\Admin;

use App\Backend\System\Resource\Controller\IndexResourceController;

class IndexController extends IndexResourceController
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
