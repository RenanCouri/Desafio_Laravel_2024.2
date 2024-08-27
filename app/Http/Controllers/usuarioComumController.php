<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\User;
use App\Models\Conta;
use App\Models\Endereco;
use Illuminate\Http\Request;

class usuarioComumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        $users=[];
        if($user->cargo=='usuario_comum')
           $users=$user;
        else if($user->cargo=='gerente' || $user->cargo=='administrador'){
           $users=$user->getUsuariosComuns();
        }
        return view('usuariosComuns.index',compact('users'));
     }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('usuariosComuns.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
           
        $controller= new RegisteredUserController();
      
        
        $complemento=null;
        if($request->hasAny('complemento'))
           $complemento=$request->complemento;
        $dadosEndereco[] = $request->only(['pais','estado','cidade','bairro','rua','numero']);
        $dadosEndereco['complemento']=$complemento;
        $endereco = Endereco::create($dadosEndereco);

        $extras=["cargo" => "usuario_comum",
                 "endereco" => $endereco->id,
        ];  
        $user=$controller->store($request,$extras);
        $dadosConta=$request->only(['numero_agencia','numero_conta','saldo','limite_transferencias','senha']);
        $dadosConta['user_id']=$user->id;
        Conta::create($dadosConta);
      }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
