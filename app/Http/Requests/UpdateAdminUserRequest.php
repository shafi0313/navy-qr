<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminUserRequest extends FormRequest
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
            'team' => ['string', 'in:A,B,C', 'required_unless:role_id,1', 'nullable'],
            'name' => ['required', 'string', 'min:1', 'max:100'],
            'email' => ['required', 'string', 'min:1', 'max:64', 'unique:users,email,'.$this->admin_user->id.'id'],
            'user_name' => ['nullable', 'string', 'min:1', 'max:32', 'unique:users,user_name,'.$this->admin_user->id.'id'],
            'mobile' => ['nullable', 'phone:BD', 'required_if:is_2fa,1'],
            'address' => ['nullable', 'string', 'min:1', 'max:191'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,JPG,png,webp,svg'],
            'is_2fa' => ['required', 'boolean', 'in:0,1'],
        ];
    }
}
