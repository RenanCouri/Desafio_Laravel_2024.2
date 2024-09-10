<?php

namespace App\Http\Requests;

use App\Models\Conta;
use App\Models\Transacao;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class TransferenciaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Request $request): bool
    {
        return $request->user()->can('requerirTransferencia',[Transacao::class, (Conta::query()->where('numero_conta',$request->conta)->where('numero_agencia',$request->agencia))->get()[0]]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'valor'=>['required','gt:0'],
            'senha_alvo'=>['required','numeric','digits:6'],
            'agencia'=>['required','string'],
            'conta'=>['required','string']
        ];
    }
}
