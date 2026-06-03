<?php

namespace App\Modules\Hotel\Application\DTOs\Hotel;

class ListHotelConfigurationsDTO
{
    public function __construct(
        public readonly string $hotelId,
        public readonly string $hotelName,
        public readonly array $configurations, // cada elemento: { roomType, accommodation, quantity }
    ) {}
}