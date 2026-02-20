<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfissionalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nivel_id' => 'required|exists:niveis,id',
            'nome' => 'required|string|max:255',
            'sexo' => 'required|in:M,F,O',
            'data_nascimento' => 'required|date|before:today',
            'hobby' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nivel_id.required' => 'Selecione um nível.',
            'nivel_id.exists' => 'O nível selecionado não existe.',
            'nome.required' => 'O nome é obrigatório.',
            'nome.max' => 'O nome não pode ter mais de 255 caracteres.',
            'sexo.required' => 'Selecione o sexo.',
            'sexo.in' => 'O sexo selecionado é inválido.',
            'data_nascimento.required' => 'Informe a data de nascimento.',
            'data_nascimento.date' => 'A data de nascimento deve ser uma data válida.',
            'data_nascimento.before' => 'A data de nascimento deve ser anterior a hoje.',
            'hobby.max' => 'O hobby não pode ter mais de 255 caracteres.',
        ];
    }
}
