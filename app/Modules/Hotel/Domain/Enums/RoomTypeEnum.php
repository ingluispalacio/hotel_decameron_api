<?php

namespace App\Modules\Hotel\Domain\Enums;

enum RoomTypeEnum: string
{
    case STANDARD = 'STANDARD';

    case JUNIOR = 'JUNIOR';

    case SUITE = 'SUITE';
}