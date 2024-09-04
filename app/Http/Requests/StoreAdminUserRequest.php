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
            'name' => ['required', 'string', 'min:1', 'max:100'],
            'email' => ['required', 'string', 'min:1', 'max:64', 'unique:users,email'],
            'user_name' => ['nullable', 'string', 'min:1', 'max:32', 'unique:users,user_name'],
            'gender' => ['nullable', 'integer', 'in:1,2,3'],
            'phone' => ['required', 'phone:BD'],
            'address' => ['required', 'string', 'min:1', 'max:191'],
            'is_active' => ['nullable', 'boolean', 'in:0,1'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,JPG,png,webp,svg'],
            'password' => ['required', 'confirmed', 'string', 'min:6', 'max:191'],
        ];
    }
}
