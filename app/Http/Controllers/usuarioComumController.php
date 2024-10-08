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
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
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
        $atual=$user->id;
        if(($user->cargo==='administrador')) 
           $users=User::query()->where('cargo','usuario_comum')->paginate(5);
        else if($user->cargo==='gerente')
           $users=User::query()->where('usuario_responsavel_id',$user->id)->where('cargo','usuario_comum')->paginate(5);
        else{
           $users[]=$user;
           $permissao=!$permissao;
        }
        return view('usuariosComuns.index',compact('users','permissao','atual'));
     }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
      $gerentes=null;
      if($request->user()->cargo==='administrador')
          $gerentes = User::query()->where('cargo','gerente')->get();
      return view('usuariosComuns.criar',compact('gerentes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EnderecoRequest $request)
    {
      Gate::authorize('createUsuarioComum', [User::class,$request->user()->id]);  
         
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
  
        
         
        $dadosConta=gerarNumeroSenhaLimiteSaldo();
        $dadosConta['numero_agencia']=gerarNumeroAgencia();
        $dadosConta['numero_conta']=gerarNumeroConta();
        $user=$controller->store($request,$extras);
        $dadosConta['user_id']=$user->id;
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
              return redirect("/editarUsuarioComum/$atual->id");
            else
               $user=$atual;
           }
           else 
        {
            $user=User::find($userId);
            if($user===null || $user->cargo !== 'usuario_comum')
                return redirect('/usuariosComuns')->withErrors('Usuário não encontrado');
            if($atual->cargo==='gerente' && !in_array($user,$atual->getUsuariosComuns()))
               return redirect('/usuariosComuns')->withErrors('Usuário não pode ser acessado por você');
        }
        $gerentes=null;
        if($atual->cargo==='administrador'){
           $gerentes=User::query()->where('cargo','gerente')->whereNot('id',$user->usuario_responsavel_id)->get();
        }
        
      
        $endereco=Endereco::find($user->endereco_id);
        return view('usuariosComuns.editar',compact('user','endereco','gerentes'));
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
        $foto=null;
        
        if($request->hasFile('foto')){
              $foto=$request->file('foto')->store('/imagens');
              $dadosUser['foto']=$foto;
        }
        
        $user->update($dadosUser);
         $user->save();
         if($request->password!=$user->password){
           $user->password=Hash::make($request->password);
           $user->save();
         } 
        ;
         return redirect('/usuariosComuns')->with('sucesso','atualização realizada com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
      if($request->user_id===null)
        return redirect('/usuariosComuns')->withErrors('Campo de id do usuário não passado');
      Gate::authorize('acaoUsuarioComum', [User::class,$request->user_id]);
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
            if($atual->cargo==='gerente' && !in_array($user,$atual->getUsuariosComuns()))
               return redirect('/usuariosComuns')->withErrors('Usuário não pode ser acessado por você');
            
        }
        $redr = $atual->id==$request->user_id;
     
        $conta=$user->conta;

        $endereco=Endereco::find($user->endereco_id);
        if($conta!==null)
          $conta->delete();
        

        

        if($redr){
         Auth::logout();
         $user->delete();
        $endereco->delete();
          return Redirect::to('/');
          $request->session()->invalidate();
        $request->session()->regenerateToken();
        }  
        $user->delete();
        $endereco->delete();
        
        return redirect('/usuariosComuns')->with('sucesso','exclusão realizada com sucesso');

    }
}
