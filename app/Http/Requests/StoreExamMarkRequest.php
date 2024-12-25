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
            'application_id' => ['required', 'exists:applications,id'],
            'bangla' => ['required', 'numeric', 'min:0', 'max:20'],
            'english' => ['required', 'numeric', 'min:0', 'max:20'],
            'math' => ['required', 'numeric', 'min:0', 'max:20'],
            'science' => ['required', 'numeric', 'min:0', 'max:20'],
            'general_knowledge' => ['required', 'numeric', 'min:0', 'max:20'],
            'viva' => ['nullable', 'numeric', 'min:0', 'max:10'],
        ];
    }
}
