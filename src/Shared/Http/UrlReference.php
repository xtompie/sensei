<?php

declare(strict_types=1);

namespace App\Shared\Http;

enum UrlReference
{
    case ABSOLUTE_URL;
    case ABSOLUTE_PATH;
    case RELATIVE_PATH;
    case NETWORK_PATH;
}
