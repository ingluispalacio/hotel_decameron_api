<?php

namespace App\Modules\Auth\Presentation\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'birth_date' => 'sometimes|required|date',
            'role_id' => 'sometimes|required|uuid|exists:roles,id',
            'email' => ['sometimes', 'required', 'string', 'email', Rule::unique('users')->ignore($this->route('user'))],
            'password' => 'sometimes|required|string|min:8',
            'address' => 'sometimes|required|string',
            'status' => ['sometimes', 'required', Rule::in(['active', 'inactive', 'blocked'])],
        ];
    }
}
