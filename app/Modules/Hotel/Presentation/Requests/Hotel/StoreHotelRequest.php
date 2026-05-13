<?php

namespace App\Modules\Hotel\Presentation\Requests\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class StoreHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for creating a hotel.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city_id' => 'required|string',
            'nit' => 'required|string|max:255',
            'max_rooms' => 'required|integer|min:1'
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'max_rooms.min' => 'Debe haber al menos 1 habitación'
        ];
    }

    /**
     * Body parameters for Scribe documentation.
     */
    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Hotel name.',
                'example' => 'Decameron Cartagena',
            ],

            'address' => [
                'description' => 'Hotel address.',
                'example' => 'Bocagrande Avenue',
            ],

            'city_id' => [
                'description' => 'City UUID associated with the hotel.',
                'example' => 'c1d2e3f4-a5b6-7890-abcd-ef1234567890',
            ],

            'nit' => [
                'description' => 'Hotel tax identification number.',
                'example' => '900123456',
            ],

            'max_rooms' => [
                'description' => 'Maximum number of rooms allowed.',
                'example' => 150,
            ],
        ];
    }
}