<?php

namespace App\Modules\Hotel\Application\DTOs\Hotel;

readonly class CreateHotelDTO
{
    public function __construct(
        public string $name,
        public string $address,
        public string $cityId,
        public string $nit,
        public int $maxRooms
    ) {}
}