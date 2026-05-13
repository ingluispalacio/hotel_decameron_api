<?php

namespace App\Modules\Hotel\Presentation\Requests\HotelConfiguration;

use Illuminate\Foundation\Http\FormRequest;

class StoreHotelConfigurationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for creating a hotel configuration.
     */
    public function rules(): array
    {
        return [
            'hotel_id' => 'required|string',
            'room_type_id' => 'required|string',
            'accommodation_id' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ];
    }

    /**
     * Body parameters for Scribe documentation.
     */
    public function bodyParameters(): array
    {
        return [
            'hotel_id' => [
                'description' => 'Hotel UUID.',
                'example' => 'f1e2d3c4-b5a6-7890-abcd-ef1234567890',
            ],

            'room_type_id' => [
                'description' => 'Room type UUID.',
                'example' => 'b1c2d3e4-f5a6-7890-abcd-ef1234567890',
            ],

            'accommodation_id' => [
                'description' => 'Accommodation UUID.',
                'example' => 'c1d2e3f4-a5b6-7890-abcd-ef1234567890',
            ],

            'quantity' => [
                'description' => 'Number of rooms configured.',
                'example' => 5,
            ],
        ];
    }
}