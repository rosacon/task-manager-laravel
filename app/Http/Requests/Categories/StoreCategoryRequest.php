<?php

namespace App\Http\Requests\Categories;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'regex:/^(?!\d+$).*/'],        
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El título es obligatorio.',
            'name.regex' => 'El título debe contener al menos una letra y puede incluir números.',
        ];
    }
}
