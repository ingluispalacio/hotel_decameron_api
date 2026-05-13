<?php

namespace App\Modules\Hotel\Domain\Rules;

use App\Modules\Hotel\Domain\Enums\RoomTypeEnum;
use App\Modules\Hotel\Domain\Enums\AccommodationEnum;

class ValidAccommodationRule
{
    public static function validate(
        RoomTypeEnum $roomType,
        AccommodationEnum $accommodation
    ): bool {

        return match ($roomType) {

            RoomTypeEnum::STANDARD =>
                in_array($accommodation, [
                    AccommodationEnum::SINGLE,
                    AccommodationEnum::DOUBLE
                ]),

            RoomTypeEnum::JUNIOR =>
                in_array($accommodation, [
                    AccommodationEnum::TRIPLE,
                    AccommodationEnum::QUADRUPLE
                ]),

            RoomTypeEnum::SUITE =>
                in_array($accommodation, [
                    AccommodationEnum::SINGLE,
                    AccommodationEnum::DOUBLE,
                    AccommodationEnum::TRIPLE
                ])
        };
    }
}