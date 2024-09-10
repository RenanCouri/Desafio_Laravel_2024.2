<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Requests\EnderecoRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Models\Conta;
use App\Models\Endereco;
use Database\Factories\GerenteFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class gerenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user=$request->user();
        if($user->cargo==='administrador')
           $users=User::query()->where('cargo','gerente')->paginate(5);
        else
           $users[]=$user;
        $permissao=true;

        $atual=$user->id;
        return view('gerentes.index',compact('users','permissao','atual'));
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
    public function store(EnderecoRequest $request)
    {
        Gate::authorize('createGerente', [User::class,$request->user()->id]);  
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
  
        $dadosConta=gerarNumeroSenhaLimiteSaldo();
        $dadosConta['numero_agencia']=gerarNumeroAgencia();
        $dadosConta['numero_conta']=gerarNumeroConta();
        $user=$controller->store($request,$extras);
        $dadosConta['user_id']=$user->id;
        Conta::create($dadosConta);
        
        return redirect('/gerentes')->with('sucesso','cadastro realizado com sucesso');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(int $gerenteId,Request $request)
    {
        $atual=$request->user();
        if($atual->cargo==='gerente')
           {
            if($atual->id!==$gerenteId)
              return redirect("/verGerente/$atual->id");
            else
               $user=$atual;
           }
        else 
        {
            $user=User::find($gerenteId);
            if($user===null || $user->cargo !== 'gerente')
                return redirect('/gerentes')->withErrors('Gerente não encontrado');
            
        }
        
    
        $endereco=Endereco::find($user->endereco_id);
        $conta=$user->conta;
      
        return view('gerentes.ver',compact('user','endereco','conta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,int $gerenteId)
    {
        
        $atual=$request->user();
        if($atual->cargo==='gerente')
           {
            if($atual->id!==$gerenteId)
              return redirect("/editarGerente/$atual->id");
            else
               $user=$atual;
           }
        else 
        {
            $user=User::find($gerenteId);
            if($user===null || $user->cargo !== 'gerente')
                return redirect('/gerentes')->withErrors('Gerente não encontrado');
            
        }
        $endereco=Endereco::find($user->endereco_id);
        return view('gerentes.editar',compact('user','endereco'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileUpdateRequest $request)
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

        $dadosUser=$request->only(['name' ,
        'email', 
        'password' ,            
        'data_nascimento', 
        'CPF',
        'numero_telefone'
        ]);
        
        $foto=null;
        
        if($request->hasFile('foto')){
              $foto=$request->file('foto')->store('/imagens');
              $dadosUser['foto']=$foto;
        } 
        $user->update(
            $dadosUser
        );
         $user->save();
         if($request->password!=$user->password){
            $user->password=Hash::make($request->password);
            $user->save();
          }
         return redirect('/gerentes')->with('sucesso','atualização realizada com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if($request->user_id===null)
          return redirect('/gerentes')->withErrors('Campo de id do gerente não passado');
        Gate::authorize('acaoGerente', [User::class,$request->user_id]);
        $atual=$request->user();
        $gerenteId=$request->user_id;
        if($atual->cargo==='gerente')
           {
            if($atual->id!=$gerenteId)
              return redirect("/gerentes")->withErrors('Esse gerente não pode ser acessado por você');
            else
               $user=$atual;
           }
        else 
        {
            $user=User::find($gerenteId);
            if($user===null || $user->cargo !== 'gerente')
                return redirect('/gerentes')->withErrors('Gerente não encontrado');
            
        }
        $comuns=$user->getUsuariosComuns();
        $possiveis=User::query()->where('cargo','gerente')->whereNot('id',$user->id)->get();
        if(sizeof($possiveis)===0){
           $gerente=(new GerenteFactory)->create();
           $dados=gerarNumeroSenhaLimiteSaldo();
            $dados['numero_agencia']= gerarNumeroAgencia();
            $dados['numero_conta']=gerarNumeroConta();
            $dados['user_id']=$gerente->id;
              Conta::create($dados); 
        }
        else{
            $gerente=$possiveis[0];
        }
        foreach($comuns as $comum){
            $comum->usuario_responsavel_id=$gerente->id;
            $comum->save();
        }
        $redr = $atual->id==$request->user_id;
     
        $conta=$user->conta;
        $endereco=Endereco::find($user->endereco_id);
        if($conta!==null)
          $conta->delete();
        $user->usuario_responsavel_id=1;
        $user->save();
        
        
        if($redr){
         Auth::logout();
         $user->delete();
        $endereco->delete();
          return Redirect::to('/');
          $request->session()->invalidate();
        $request->session()->regenerateToken();
        }  
        else{
            $user->delete();
        $endereco->delete();
        }
        return redirect('/gerentes')->with('sucesso','exclusão realizado com sucesso');
    }
}
