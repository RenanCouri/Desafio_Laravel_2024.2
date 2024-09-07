<?php

namespace App\Http\Requests;

use App\Models\Pendencia;
use App\Policies\PendenciaPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PendenciaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Request $request): bool
    {
        
        return $request->user()->can('acaoPendencia',Pendencia::find($request->pendencia_id)) ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'aprovado'=>['required','boolean'],
            'pendencia_id'=>['required','numeric','gt:0']
        ];
    }
}
