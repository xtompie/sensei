<?php

declare(strict_types=1);

namespace App\Backend\Resource\BackendUser;

use App\Backend\System\Resource\Controller\IndexResourceController;

class IndexController extends IndexResourceController
{
    /**
     * @return array<int,string>
     */
    protected function filters(): array
    {
        return [
            'id:match',
            'email',
            'role',
        ];
    }

    /**
     * @return array<int,string>
     */
    protected function orders(): array
    {
        return [
            'id',
            'email',
            'role',
        ];
    }
}
