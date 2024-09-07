<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnderecoRequest extends FormRequest
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
            'pais' => ['required','string','max:70'],
            'estado'=>['required','string','max:70'],
            'cidade'=>['required','string','max:70'],
            'bairro'=>['required','string','max:70'],
            'rua'=>['required','string','max:100'],
            'numero_predial'=>['required','numeric','max_digits:12'],
            'completemento'=>['numeric','max_digits:12']
        ];
    }
}
