<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\User;
use App\Models\Conta;
use App\Models\Endereco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class gerenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users=User::query()->where('cargo','gerente')->get();
        $permissao=true;
        return view('gerentes.index',compact('users','permissao'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gerentes.criar');
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
         
        $extras=["cargo" => "gerente",
                 "endereco_id" => $endereco->id,
        ];
  
        $user=$controller->store($request,$extras);
      
        $dadosConta=$request->only(['numero_agencia','numero_conta','limite_transferencias','senha']);
        $dadosConta['user_id']=$user->id;
        $dadosConta['saldo']=0;
        Conta::create($dadosConta);
        return redirect('/gerentes');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $gerenteId,Request $request)
    {
        $atual=$request->user();
        $user=User::find($gerenteId);
            if(($atual->cargo==='gerente' && !$atual->getGerentes()->contains($user)) ||$user===null|| $user->cargo!=='gerente')
               return redirect('/gerentes');
    
        $endereco=Endereco::find($user->endereco_id);
        $conta=$user->conta;
      
        return view('gerentes.ver',compact('user','endereco','conta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,int $gerenteId)
    {
        $user=User::find($gerenteId);
        $atual=$request->user();
        $user=User::find($gerenteId);
            if($user===null || $user->cargo !== 'gerente')
                return redirect('/gerentes');
            if($atual->cargo==='gerente' && !$atual->getGerentes()->contains($user))
               return redirect('/gerentes');
       
        $endereco=Endereco::find($user->endereco_id);
        return view('gerentes.editar',compact('user','endereco'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user=User::find($request->user_id);
        $endereco=Endereco::find($user->endereco_id);
        $complemento=null;
        if($request->hasAny('completemento'))
           $complemento=$request->completemento;
        $dadosEndereco = $request->only(['pais','estado','cidade','bairro','rua','numero_predial']);
        $dadosEndereco['completemento']=$complemento;

        $endereco->update($dadosEndereco);
        $endereco->save();

        $user->update(
            $request->only(['name' ,
            'email', 
            'password' ,            
            'data_nascimento', 
            'CPF',
            'numero_telefone'
            ]
            )
        );
         $user->save();
         return redirect('/gerentes');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user,Request $request)
    {
        $user = User::find($request->user_id);
        
        $atual= $request->user();
        $redr = $atual->id==$request->user_id;
     
        $conta=$user->conta;
        $conta->delete();
        $user->delete();

        

        if($redr){
         Auth::logout();
          return Redirect::to('/');
          $request->session()->invalidate();
        $request->session()->regenerateToken();
        }  
        return Redirect::to('/gerentes');
    }
}
