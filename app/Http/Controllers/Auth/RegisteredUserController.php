<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
    public function store(Request $request,array $extras)
    {
        $dataIni=new date('1899-12-31');
       
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required' , Rules\Password::defaults()],
            'data_nascimento' =>['date_format:Y-m-d','required',"after:1899-12-31","before:today"],
            'numero_cpf'=>['required','string','size:14'],
            'numero_telefone'=>['required','numeric','digits:12'],
            'foto' => [Rule::requiredIf($extras['cargo']!=='usuario_comum'),'extensions:jpg,png'],
            'usuario_responsavel_id'=>[Rule::prohibitedIf($request->user()->cargo!=='administrador'),'numeric','gte:1']

        ]);
        
        $foto=null;
        if($request->hasFile('foto'))
           $foto=$request->foto;
        $dados=[
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'data_nascimento' => $request->data_nascimento,
            'CPF'=>$request->numero_cpf,
            'numero_telefone' => $request->numero_telefone,
            'usuario_responsavel_id' => 1,
            'foto' => $foto,
            'endereco_id' => 2,
            'cargo' => $extras['cargo']
        ]; 
        //dd($dados);
        $user = User::create($dados);

        event(new Registered($user));

        //Auth::login($user);

        return $user;
    }
}
