<?php

namespace App\Modules\Hotel\Application\DTOs\Hotel;

readonly class UpdateHotelDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $address,
        public string $cityId,
        public string $nit,
        public int $maxRooms
    ) {}
}