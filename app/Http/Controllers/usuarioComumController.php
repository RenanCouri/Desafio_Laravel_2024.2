<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Requests\EnderecoRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Models\Conta;
use App\Models\Endereco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function store(EnderecoRequest $request)
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
        return redirect('/usuariosComuns')->with('sucesso','cadastro realizado com sucesso');
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
            if($user===null || $user->cargo !== 'usuario_comum')
                return redirect('/usuariosComuns')->withErrors('Usuário não encontrado');
            if($atual->cargo==='gerente' && !in_array($user,$atual->getUsuariosComuns()) )
              return redirect('/usuariosComuns')->withErrors('Usuário não pode ser acessado por você');
        }
        $endereco=Endereco::find($user->endereco_id);
        $conta=$user->conta;
      
        return view('usuariosComuns.ver',compact('user','endereco','conta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $userId,Request $request)
    {
        $user=User::find($userId);
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
            if($user===null || $user->cargo !== 'usuario_comum')
                return redirect('/usuariosComuns')->withErrors('Usuário não encontrado');
            if($atual->cargo==='gerente' && !$atual->getUsuariosComuns()->contains($user))
               return redirect('/usuariosComuns')->withErrors('Usuário não pode ser acessado por você');
        }
        
      
        $endereco=Endereco::find($user->endereco_id);
        return view('usuariosComuns.editar',compact('user','endereco'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileUpdateRequest $request)
    {
        
      
        $user=User::find($request->user_id);
        $respId=$user->usuario_responsavel_id;
        if($request->usuario_responsavel_id!==null){
         $userResp=User::find($request->usuario_responsavel_id);
         if($userResp===null || $userResp->cargo!=='gerente')
            return redirect('/usuariosComuns')->withErrors('Dados de gerente responsável inválidos passados'); 
         else
           $respId=$userResp->id; 
      } 
        $endereco=Endereco::find($user->endereco_id);
        $complemento=null;
        if($request->hasAny('completemento'))
           $complemento=$request->completemento;
        $dadosEndereco = $request->only(['pais','estado','cidade','bairro','rua','numero_predial']);
        $dadosEndereco['completemento']=$complemento;

        $endereco->update($dadosEndereco);
        $endereco->save();
        $dadosUser=$request->only(['name' ,
        'email', 
        'password' ,            
        'data_nascimento', 
        'CPF',
        'numero_telefone'
        ]);
        $dadosUser['usuario_responsavel_id']=$respId;
        $user->update($dadosUser);
         $user->save();
         return redirect('/usuariosComuns')->with('sucesso','atualização realizada com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $userId=$request->user_id;
        $user=User::find($userId);
        $atual=$request->user();
        if($atual->cargo==='usuario_comum')
           {
            if($atual->id!==$userId)
              return redirect("/usuariosComuns");
            else
               $user=$atual;
           }
           else 
        {
            $user=User::find($userId);
            if($user===null || $user->cargo !== 'usuario_comum')
                return redirect('/usuariosComuns')->withErrors('Usuário não encontrado');
            if($atual->cargo==='gerente' && !$atual->getUsuariosComuns()->contains($user))
               return redirect('/usuariosComuns')->withErrors('Usuário não pode ser acessado por você');
            
        }
        $redr = $atual->id==$request->user_id;
     
        $conta=$user->conta;
        $endereco=$user->id;
        $conta->delete();
        $user->delete();
        $endereco->delete();

        

        if($redr){
         Auth::logout();
          return Redirect::to('/');
          $request->session()->invalidate();
        $request->session()->regenerateToken();
        }  
        return redirect('/usuariosComuns')->with('sucesso','exclusão realizada com sucesso');

    }
}
