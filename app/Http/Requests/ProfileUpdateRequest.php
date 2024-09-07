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
    public function authorize(Request $request): bool
    {
        if($request->route()->uri === "editarUsuarioComum"){
          return $request->user()->can('acaoUsuarioComum',[User::class, $request->user_id]) ;
        }
        else if($request->route()->uri === "editarGerente"){
            return $request->user()->can('acaoGerente',[User::class, $request->user_id]) ;
          }
        else {
            return $request->user()->can('acaoAdministrador',[User::class, $request->user_id]) ;
        }
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    
    public function rules(EnderecoRequest $request): array
    {
        $terFoto=($request->route()->uri !== "editarUsuarioComum");
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($request->user_id)],
            'data_nascimento' =>['date_format:Y-m-d','required',"after:1899-12-31","before:today"],
            'numero_cpf'=>['required','string','size:14'],
            'numero_telefone'=>['required','numeric','digits:12'],
            'foto' => [Rule::requiredIf($terFoto)],
            'usuario_responsavel_id'=>[Rule::prohibitedIf($request->user()->cargo!=='administrador' || $terFoto),Rule::requiredIf(!$terFoto && $request->user()->cargo==='administrador'),'numeric','gte:1']
        ];
    }
}
