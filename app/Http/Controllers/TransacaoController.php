<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\Pendencia;
use App\Models\Transacao;
use App\Models\User;
use Illuminate\Http\Request;

class TransacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user=$request->user();
        $transacoes=$user->conta->transacoes;
        return view('transacoes.index',compact('transacoes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function transferencia()
    {
        return view('transacoes.transferencia');
    }
    public function saque_deposito(){
        return view('transacao.saque_deposito');
    }
    public function sacar(Request $request)
    {
        $dados=$request->only(['valor']);

        $conta=Conta::query()->where('numero_conta',$request->numero_conta)->get()[0];
        $atual=$request->user();
        if($atual->cargo==='gerente' && !($atual->id!=$conta->user_id || !$atual->getUsuariosComuns()->contains(User::find($conta->id))))
           return redirect()->back();
        $dados['autoridade_id']=$atual->id;
        if($conta->sacar($request->valor)){
        $dados['conta_destinatario_id']=$dados['conta_remetente_id']=$conta->id;
        $dados['tipo']='saque';
        $dados['esta_pendente']=false;
        
           $transacao= Transacao::create([$dados]);
           $conta->saldo-=$request->valor;
        }   
        return redirect()->back();
    }
    public function depositar(Request $request)
    {
        $dados=$request->only(['valor']);
        $conta=Conta::query()->where('numero_conta',$request->numero_conta)->get()[0];
        $atual=$request->user();
        if($conta!=null && $conta->depositar($request->valor)){
            if($atual->cargo==='gerente' && !($atual->id!=$conta->user_id || !$atual->getUsuariosComuns()->contains(User::find($conta->id))))
            return redirect()->back();
         $dados['autoridade_id']=$atual->id;
        $dados['conta_destinatario_id']=$dados['conta_remetente_id']=$conta->id;
        $dados['tipo']='deposito';
        $dados['esta_pendente']=false;
        Transacao::create([$dados]);
        
         
        
       }
       return redirect()->back();
    }
    public function requerirTransferencia(Request $request){

        $dados=$request->only(['valor']);
        $dados['tipo']='transferencia';
        $dados['esta_pendente']=true;
        $contaRem=Conta::find($request->user()->conta);
        $contaDes=Conta::query()->where('numero_conta',$request->numero_conta)->get()[0];
        $dados['conta_destinatario_id']=$contaDes->id;
        $dados['conta_remetente_id']=$contaRem->id;
        if($contaRem->saldo>=$request->valor && $request->valor>0)
        {
           $transacao= Transacao::create([$dados]);
           if($contaRem->limite_transferencias<$request->valor)
           {
            Pendencia::create(['titulo'=>'Valor de Transferência excede os limites',
                                'tipo'=>'transferencia',
                                'foi_resolvida'=>false,
                                'transacao_id'=>$transacao->id,
                                'emprestimo_id'=>false

            ]);
           }
           else{
            $transacao->esta_prendente=false;
            $this->transferir($contaRem,$contaDes,$request->valor);
            }
        }
        return redirect()->back();
    }
    public function reailzarTransferenciaPendente(Request $request){
        $transacao=Transacao::find($request->transacao_id);
        if($transacao===null || $request->user()->id!= $transacao->autoridade_id)
           return redirect()->back();
        $transacao->esta_prendente=false;
        $this->transferir(Conta::find($transacao->conta_remetente_id),Conta::find($transacao->conta_destinatario_id),$request->valor);
        return redirect()->back();
        
    }
    private function transferir(Conta $contaRem, Conta $contaDes, int $valor){
        if($contaRem!==null && $contaDes!==null){
            if($contaRem->sacar($valor))
               $contaDes->depositar($valor);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transacao $transacao)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transacao $transacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transacao $transacao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transacao $transacao)
    {
        //
    }
}
