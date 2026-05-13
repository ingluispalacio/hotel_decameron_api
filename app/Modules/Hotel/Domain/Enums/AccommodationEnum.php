<?php

namespace App\Modules\Hotel\Domain\Enums;

enum AccommodationEnum: string
{
    case SINGLE = 'SINGLE';

    case DOUBLE = 'DOUBLE';

    case TRIPLE = 'TRIPLE';

    case QUADRUPLE = 'QUADRUPLE';
}