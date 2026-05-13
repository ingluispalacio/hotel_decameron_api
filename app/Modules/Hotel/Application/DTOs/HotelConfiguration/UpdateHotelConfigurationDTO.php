<?php

namespace App\Modules\Hotel\Application\DTOs\HotelConfiguration;

readonly class UpdateHotelConfigurationDTO
{
    public function __construct(
        public string $id,
        public string $hotelId,
        public string $roomTypeId,
        public string $accommodationId,
        public int $quantity
    ) {}
}