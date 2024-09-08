<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnderecoRequest;
use App\Models\User;
use DateTime;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(EnderecoRequest $request,array $extras)
    {
       $terFoto=$extras['cargo']!=='usuario_comum';
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required' , Rules\Password::defaults()],
            'data_nascimento' =>['date_format:Y-m-d','required',"after:1899-12-31","before:today"],
            'numero_cpf'=>['required','string','size:14'],
            'numero_telefone'=>['required','numeric','digits:12'],
            'foto' => [Rule::requiredIf($terFoto),'extensions:png,jpg'],
            'usuario_responsavel_id'=>[Rule::prohibitedIf($request->user()->cargo!=='administrador' || $terFoto),Rule::requiredIf(!$terFoto && $request->user()->cargo==='administrador'),'numeric','gte:1']
            
        ]);
        $foto=null;
        
        if($request->hasFile('foto')){
            $foto=$request->file('foto')->store('imagens');
        }
        $request->usuario_responsavel_id ? $userResp=$request->usuario_responsavel_id : ( $extras['cargo'] !== 'gerente' ? $userResp=$request->user()->id : $userResp=1);
        $dados=[
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'data_nascimento' => $request->data_nascimento,
            'CPF'=>$request->numero_cpf,
            'numero_telefone' => $request->numero_telefone,
            'usuario_responsavel_id' => $userResp,
            'foto' => $foto,
            'endereco_id' => $extras['endereco_id'],
            'cargo' => $extras['cargo']
        ]; 
        $user = User::create($dados);
        
        event(new Registered($user));
        if($extras['cargo'] === 'gerente'){
            $user->usuario_responsavel_id=$user->id;
            $user->save();
        }
            
        //Auth::login($user);

        return $user;
    }
}
