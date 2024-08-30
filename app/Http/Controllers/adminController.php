<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\Endereco;
use App\Models\User;
use Illuminate\Http\Request;

class adminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users=User::query()->where('cargo','administrador')->get();
        $permissao=true;
        return view('administradores.index',compact('users','permissao'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administradores.criar');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $controller= new RegisteredUserController();
      
        
        $complemento=null;
        if($request->hasAny('completemento'))
           $complemento=$request->completemento;
        $dadosEndereco = $request->only(['pais','estado','cidade','bairro','rua','numero_predial']);
        $dadosEndereco['completemento']=$complemento;
     
        $endereco = Endereco::create($dadosEndereco);
         
        $extras=["cargo" => "administrador",
                 "endereco_id" => $endereco->id,
        ];
  
        $controller->store($request,$extras);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $admId,Request $request)
    {
        $atual=$request->user();
        $user=User::find($admId);
            if( $user!==null && $user->cargo!=='administrador')
               return redirect('/administradores');
    
        $endereco=Endereco::find($user->endereco_id);
      
        return view('administradores.ver',compact('user','endereco'));
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
