<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\User;
use App\Models\Conta;
use App\Models\Endereco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class usuarioComumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /*$users=[];
        if($user->cargo=='usuario_comum')
           $users=$user;
        else if($user->cargo=='gerente' || $user->cargo=='administrador'){
           $users=$user->getUsuariosComuns();
        }
           */
        $permissao=true;  
        $user=$request->user(); 
        if(($user->cargo==='administrador')) 
           $users=$user->where('cargo','usuario_comum')->get();
        else if($user->cargo==='gerente')
           $users=$user->getUsuariosComuns();
        else{
           $users[]=$user;
           $permissao=!$permissao;
        }
        return view('usuariosComuns.index',compact('users','permissao'));
     }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('usuariosComuns.criar');
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
         
        $extras=["cargo" => "usuario_comum",
                 "endereco_id" => $endereco->id,
        ];
  
        $user=$controller->store($request,$extras);
      
        $dadosConta=$request->only(['numero_agencia','numero_conta','limite_transferencias','senha']);
        $dadosConta['user_id']=$user->id;
        $dadosConta['saldo']=0;
        Conta::create($dadosConta);
      }

    /**
     * Display the specified resource.
     */
    public function show(int $userId,Request $request)
    {
        $atual=$request->user();
        if($atual->cargo==='usuario_comum')
           {
            if($atual->id!==$userId)
              return redirect("/verUsuarioComum/$atual->id");
            else
               $user=$atual;
           }
        else 
        {
            $user=User::find($userId);
            if($user!==null && (($atual->cargo==='gerente' && !$atual->getUsuariosComuns()->contains($user)) || $user->cargo!=='usuario_comum'))
            return redirect('/usuariosComuns');
        }
        $endereco=Endereco::find($user->endereco_id);
        $conta=$user->conta;
      
        return view('usuariosComuns.ver',compact('user','endereco','conta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $userId)
    {
        $user=User::find($userId);
        return view('usuariosComuns.view',$user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        
        $user = $request->user();

        $redr = boolval($user->id===$request->id);
        $conta=$user->conta();
        $conta->delete();
        $control = new ProfileController();
        $control->destroy($request);
        if($redr)
          return Redirect::to('/');
        return Redirect::to('/usuariosComuns');

    }
}
