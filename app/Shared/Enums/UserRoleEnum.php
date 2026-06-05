<?php

declare(strict_types=1);

namespace App\Shared\Enums;

enum UserRoleEnum: string
{
    case ADMIN = 'ADMIN';
    case CLIENT = 'CLIENT';
}
