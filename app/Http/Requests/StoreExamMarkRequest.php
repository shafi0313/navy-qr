<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamMarkRequest extends FormRequest
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
            'id'                => ['required', 'exists:applications,id'],
            'bangla'            => ['required', 'numeric'],
            'english'           => ['required', 'numeric'],
            'math'              => ['required', 'numeric'],
            'science'           => ['required', 'numeric'],
            'general_knowledge' => ['required', 'numeric'],
            'viva'              => ['nullable', 'numeric'],
        ];
    }
}
