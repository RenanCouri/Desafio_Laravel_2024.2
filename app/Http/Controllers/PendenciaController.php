<?php

namespace App\Http\Controllers;

use App\Http\Requests\PendenciaRequest;
use App\Models\Emprestimo;
use App\Models\Pendencia;
use App\Models\Transacao;
use Illuminate\Http\Request;

class PendenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pendenciasTeste= $request->user()->getPendenciasNaoResolvidas();
        $pendencias=[];
        foreach($pendenciasTeste as $pendencia){
            if( ($pendencia->tipo==='transferencia' && $pendencia->transacao->conta_remetente_id!==null) || ($pendencia->tipo==='emprestimo' && $pendencia->emprestimo->conta_id!==null))
            {
                $pendencias[]=$pendencia;
            }
            else{
                $pendencia->foi_resolvida=true;
                $pendencia->save();
                
            }
        }
        return view('pendencias.index',compact('pendencias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function acao(PendenciaRequest $request)
    {
        $pendencia=Pendencia::find($request->id);
        if($pendencia->foi_resolvida)
          return redirect('/pendencias')->withErrors('Você não pode alterar uma pendência já resolvida');
        if($pendencia!==null){
          
            $transferencia=Transacao::find($pendencia->transacao_id);
            $emprestimo=Emprestimo::find($pendencia->emprestimo_id);
            
            if($request->aprovado == 1){
              if($pendencia->tipo==='transferencia'){
                (New TransacaoController())->realizarTransferenciaPendente($transferencia);
             }
             else{
                (New EmprestimoController())->efetuar($emprestimo);
             }
             }
             if( $request->aprovado == 0)
             {
              
                if($pendencia->tipo==='emprestimo'){
                    $emprestimo->esta_pendente=false;
                   $emprestimo->foi_aprovado=false;
                   $emprestimo->save();
                   
                  }
                  
              }
        $pendencia->foi_resolvida=true;
            $pendencia->save();
        }
        return redirect('/pendencias');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data=$request->only(['titulo','tipo']);
        $data['foi_resolvida']=false;  
        if($request->tipo=='transacao')
           $data['transacao_id']=$request->transacao_id;
        if($request->tipo=='emprestimo')
           $data['emprestimo_id']=$request->emprestimo_id;

        Pendencia::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pendencia $pendencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pendencia $pendencia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pendencia $pendencia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pendencia $pendencia)
    {
        //
    }
}
