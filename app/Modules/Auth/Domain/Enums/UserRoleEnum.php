<?php

namespace App\Modules\Auth\Domain\Enums;

enum UserRoleEnum: string
{
    case ADMIN = 'ADMIN';
    case CLIENT = 'CLIENT';
}