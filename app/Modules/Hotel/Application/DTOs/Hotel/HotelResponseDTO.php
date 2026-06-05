<?php

declare(strict_types=1);

namespace App\Modules\Hotel\Application\DTOs\Hotel;

final readonly class HotelResponseDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $address,
        public string $cityName,
        public string $nit,
        public int $maxRooms
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'city_name' => $this->cityName,
            'nit' => $this->nit,
            'max_rooms' => $this->maxRooms,
        ];
    }
}
