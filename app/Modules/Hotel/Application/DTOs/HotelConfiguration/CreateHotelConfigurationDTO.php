<?php

namespace App\Modules\Hotel\Application\DTOs\HotelConfiguration;

readonly class CreateHotelConfigurationDTO
{
    public function __construct(
        public string $hotelId,
        public string $roomTypeId,
        public string $accommodationId,
        public int $quantity
    ) {}
}