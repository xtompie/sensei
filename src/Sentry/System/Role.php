<?php

declare(strict_types=1);

namespace App\Sentry\System;

enum Role: string
{
    case GUEST = 'guest';
    case SUPERADMIN = 'superadmin';
    case ADMIN = 'admin';
}
