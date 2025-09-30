<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'team' => ['nullable', 'in:A,B,C'],
            'name' => ['required', 'string', 'min:1', 'max:100'],
            'email' => ['required', 'string', 'min:1', 'max:64', 'unique:users,email'],
            'mobile' => ['nullable', 'phone:BD', 'required_if:is_2fa,1'],
            'address' => ['nullable', 'string', 'min:1', 'max:191'],
            // 'image' => ['nullable', 'image', 'mimes:jpeg,jpg,JPG,png,webp,svg'],
            'password' => ['required', 'confirmed', 'string', 'min:6', 'max:191'],
            'is_2fa' => ['required', 'boolean', 'in:0,1'],
        ];
    }
}
