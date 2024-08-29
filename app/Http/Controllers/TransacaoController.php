<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\Pendencia;
use App\Models\Transacao;
use Illuminate\Http\Request;

class TransacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function sacar(Request $request)
    {
        $dados=$request->only(['valor','conta_remetente_id','autoridade_id']);
        $dados['conta_destinatario_id']=$request->conta_remetente_id;
        $dados['tipo']='saque';
        $dados['esta_pendente']=false;
        $conta=Conta::find($request->conta_remetente_id);
        if($conta->saldo>=$request->valor){
           $transacao= Transacao::create([$dados]);
           $conta->saldo-=$request->valor;
        }   
        return redirect()->back();
    }
    public function depositar(Request $request)
    {
        $dados=$request->only(['valor','autoridade_id']);
        $conta=Conta::query()->where('numero_conta',$request->numero_conta)->get()[0];
        $dados['conta_destinatario_id']=$dados['conta_remetente_id']=$conta->id;
        $dados['tipo']='deposito';
        $dados['esta_pendente']=false;
        $transacao= Transacao::create([$dados]);
        $conta->saldo+=$request->valor;
         
        return redirect()->back();
    }
    public function requerirTransferencia(Request $request){

        $dados=$request->only(['valor','conta_remetente_id']);
        $dados['tipo']='transferencia';
        $dados['esta_pendente']=true;
        $contaRem=Conta::find($request->conta_remetente_id);
        $contaDes=Conta::query()->where('numero_conta',$request->numero_conta)->get()[0];
        $dados['conta_destinatario_id']=$contaDes->id;
        if($contaRem->saldo>=$request->valor)
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
            $contaRem->saldo -= $request->valor;
            $contaDes->saldo += $request->valor;
            }
        }
        return redirect()->back();
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
