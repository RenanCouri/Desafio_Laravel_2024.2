<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class EmprestimoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Request $request): bool
    {
        return $request->user()->can('paginaEmprestimo');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'valor'=>['gt:0','numeric'],
            'valor_a_pagar'=>['exclude_if:valor,required','exclude_unless:valor,prohibited','gt:0','numeric'],
            'senha'=>['required','numeric','digits:6']

        ];
    }
}
