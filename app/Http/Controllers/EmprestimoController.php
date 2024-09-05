<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\Emprestimo;
use App\Models\Pendencia;
use App\Models\Transacao;
use Illuminate\Http\Request;

class EmprestimoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $conta=$request->user()->conta;
        $emprestimo=$conta->getEmprestimosNaoPagosOuPendentes();
       return view('emprestimo.index',compact('emprestimo'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function solicitacao(Request $request)
    {
        if($request->hasAny('valor_a_pagar'))
           return $this->pagamento($request);
        if($request->user()->conta->senha===$request->senha
        && $request->user()->conta->getEmprestimosNaoPagosOuPendentes()===null){
              $emprestimo = Emprestimo::create(['valor'=> $request->valor,
                 'esta_pendente'=>true,
                 'foi_aprovado'=>null,
                 'quantidade_a_pagar'=>$request->valor,
                 'conta_id'=>$request->user()->conta->id
        ]);
              Pendencia::create([
                'titulo'=> 'Pedido de empréstimo',
                'foi_resolvida'=>false,
                'tipo'=>'emprestimo',
                'autoridade_id'=>$request->user()->usuario_responsavel_id,
                'emprestimo_id'=>$emprestimo->id
              ]
              );
        }
        return redirect('/emprestimo');
    }
    public function efetuar(Emprestimo $emprestimo)
    {
                $emprestimo->esta_pendente=false;
                $emprestimo->foi_aprovado=true;
                
                $emprestimo->quantidade_a_pagar=$emprestimo->valor;
                $emprestimo->save();
                $conta=Conta::find($emprestimo->conta_id);
                $conta->depositar($emprestimo->valor);
                
    }
    public function pagamento(Request $request){
        $emprestimo=Emprestimo::find($request->emprestimo_id);
        $conta = $request->user()->conta;
        if($conta->senha!=$request->senha|| $emprestimo ===null || !$emprestimo->foi_aprovado || $emprestimo->quantidade_a_pagar==0 || $emprestimo->conta_id!==$conta->id || $request->valor_a_pagar>$emprestimo->quantidade_a_pagar)
           return redirect()->back();

        if(!$conta->sacar($request->valor_a_pagar))
           return redirect()->back();
        $emprestimo->quantidade_a_pagar-=$request->valor_a_pagar;
  
        if($emprestimo->quantidade_a_pagar==0)
           $emprestimo->data_pagamento=now();
        ;
        Transacao::create(['valor'=> $request->valor_a_pagar,
        'tipo'=>'pagamento_emprestimo',
        'esta_pendente'=>false,
        'conta_remetente_id'=>$conta->id,
        'conta_destinatario_id'=>$conta->id,
        'autoridade_id'=>$request->user()->usuario_responsavel_id]);
        $emprestimo->save();
        return redirect('/emprestimo');
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
    public function show(Emprestimo $emprestimo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Emprestimo $emprestimo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Emprestimo $emprestimo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Emprestimo $emprestimo)
    {
        //
    }
}
