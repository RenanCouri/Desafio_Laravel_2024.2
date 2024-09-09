<?php

namespace App\Http\Requests;

use App\Models\Conta;
use App\Models\Transacao;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class SaqueDepositoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function authorize(Request $request): bool
    {
        return $request->user()->can('saqueDeposito',[Transacao::class,Conta::query()->where('numero_conta',$request->conta)->where('numero_agencia',$request->agencia)->get()[0]]);
    }

    public function rules(): array
    {
        
        return [
            'valor' => ['required', 'numeric', 'gt:0'],
            'agencia'=>['required','string','max:20'],
            'conta'=>['required','string','max:20'],
            'senha_alvo'=>['required','numeric','digits:6'],
            'senha_agente'=>['required','string','current_password'],
            'tipo' => 'exclude_if:same:deposito,same:saque'
            
        ];
    }
}
