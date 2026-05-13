<?php

namespace App\Modules\Auth\Presentation\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',
            'address' => 'required|string',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set role_id to 'client' internally
        $clientRole = \App\Modules\Auth\Infrastructure\Models\Role::where('title', 'client')->first();
        if ($clientRole) {
            $this->merge([
                'role_id' => $clientRole->id,
                'status' => 'active',
            ]);
        }
    }
}
