<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'name' => 'required|string|max:150|regex:/^[a-zA-Z0-9\s.,!?()\'"-]+$/',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The task name is required',
            'name.max' => 'The task name may not be greater than 150 characters',
        ];
    }
}
