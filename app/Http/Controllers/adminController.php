<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Requests\EnderecoRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Endereco;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class adminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $responsavelId=$request->user()->usuario_responsavel_id;
        $admCriador=User::find($responsavelId);
        $users=User::query()->where('cargo','administrador')->whereNot('id',$responsavelId)->get();
        $permissao=true;
        return view('administradores.index',compact('users','permissao','admCriador'));
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
    public function store(EnderecoRequest $request)
    {
        $controller= new RegisteredUserController();
        $foto=$request->file('foto')->store('imagens');
        
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
        return redirect('/administradores');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $admId,Request $request)
    {
        $atual=$request->user();
        $user=User::find($admId);
            if( $user!==null && $user->cargo!=='administrador')
               return redirect('/administradores')->withErrors('Administrador não encontrado');
            if($user->id===$atual->usuario_responsavel_id)
             return redirect('/administradores')->withErrors('Você não tem permissão para realizar qualquer ação a esse administrador');
        $endereco=Endereco::find($user->endereco_id);
      
        return view('administradores.ver',compact('user','endereco'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $admId,Request $request)
    {
        $atual=$request->user();
        $user=User::find($admId);
            if( $user===null|| $user->cargo!=='administrador')
               return redirect('/administrador');
    
        $endereco=Endereco::find($user->endereco_id);
        
      
        return view('administradores.editar',compact('user','endereco'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileUpdateRequest $request, User $user)
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
         return redirect('/administradores');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $user = User::find($request->user_id);
        
        $atual= $request->user();
        $redr = $atual->id==$request->user_id;

        $user->delete();

        

        if($redr){
         Auth::logout();
         $request->session()->invalidate();
        $request->session()->regenerateToken();
          return Redirect::to('/');
          
        }  
        return Redirect::to('/administradores');
    }
}
