<?php

namespace App\Modules\Hotel\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HotelConfigurationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->getId(),
            'hotel_id' => $this->getHotelId(),
            'room_type_id' => $this->getRoomTypeId(),
            'accommodation_id' => $this->getAccommodationId(),
            'quantity' => $this->getQuantity(),
        ];
    }
}