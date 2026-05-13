<?php

namespace App\Modules\Auth\Domain\Enums;

enum UserStatusEnum: string
{
    case ACTIVE = 'ACTIVE' | 'active';
    case INACTIVE = 'INACTIVE' | 'inactive';
}