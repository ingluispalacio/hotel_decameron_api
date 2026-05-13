<?php

namespace App\Modules\Auth\Presentation\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
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
            'role_id' => 'required|uuid|exists:roles,id',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',
            'address' => 'required|string',
            'status' => ['required', Rule::in(['active', 'inactive', 'blocked'])],
        ];
    }
}
