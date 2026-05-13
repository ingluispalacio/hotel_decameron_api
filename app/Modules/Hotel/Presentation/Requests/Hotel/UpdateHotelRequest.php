<?php

namespace App\Modules\Hotel\Presentation\Requests\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for updating a hotel.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city_id' => 'required|string',
            'nit' => 'required|string|max:255',
            'max_rooms' => 'required|integer|min:1',
        ];
    }

    /**
     * Body parameters for Scribe documentation.
     */
    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Updated hotel name.',
                'example' => 'Decameron Barú',
            ],

            'address' => [
                'description' => 'Updated hotel address.',
                'example' => 'Barú Island, Cartagena',
            ],

            'city_id' => [
                'description' => 'City UUID associated with the hotel.',
                'example' => 'c1d2e3f4-a5b6-7890-abcd-ef1234567890',
            ],

            'nit' => [
                'description' => 'Updated hotel tax identification number.',
                'example' => '900987654',
            ],

            'max_rooms' => [
                'description' => 'Updated maximum number of rooms.',
                'example' => 250,
            ],
        ];
    }
}