<?php

namespace App\Http\Requests;

use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public $terFoto=true;
    public function rules(Request $request): array
    {
        $terFoto=($request->route()->uri !== "editarUsuarioComum");
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($request->user_id)],
            'data_nascimento' =>['date_format:Y-m-d','required',"after:1899-12-31","before:today"],
            'numero_cpf'=>['required','string','size:14'],
            'numero_telefone'=>['required','numeric','digits:12'],
            'foto' => [Rule::requiredIf($terFoto),'extensions:jpg,png'],
            'usuario_responsavel_id'=>[Rule::prohibitedIf($request->user()->cargo!=='administrador'),'numeric','gte:1']
        ];
    }
}
