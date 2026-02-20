<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NivelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $nivelId = $this->route('id');

        return [
            'nivel' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ\s]+$/',
                'unique:niveis,nivel' . ($nivelId ? ',' . $nivelId : ''),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nivel.required' => 'O nome do nível é obrigatório.',
            'nivel.max' => 'O nome do nível não pode ter mais de 255 caracteres.',
            'nivel.regex' => 'O nome do nível deve conter apenas letras e espaços.',
            'nivel.unique' => 'Já existe um nível com este nome.',
        ];
    }
}
