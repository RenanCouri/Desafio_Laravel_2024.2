<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmailRequest extends FormRequest
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
            'cargos_checagem' => [
        'required',
        'array',
          ],
    'cargos_checagem.*' => Rule::in(['administrador', 'gerente','usuario_comum']),
    'titulo_email'=>['required','string','max:64'],
    'conteudo_email'=>['required','string','max:255']
        ];
    }
}
