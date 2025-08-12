<?php

namespace App\Http\Requests\Tasks;

use App\Models\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Task::class);
    }


    protected function prepareForValidation()
    {
        $this->merge([
            'completed' => $this->has('completed'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'regex:/^(?!\d+$).*/'],
            'description' => ['nullable', 'string'],
            'completed' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'title.regex' => 'El título debe contener al menos una letra y puede incluir números.',
        ];
    }
}
