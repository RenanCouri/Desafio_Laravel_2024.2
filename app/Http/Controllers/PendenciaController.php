<?php

namespace App\Http\Controllers;

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
        $pendencias= $request->user()->getPendenciasNaoResolvidas();
        return view('pendencias.index',compact('pendencias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function acao(Request $request)
    {
        $pendencia=Pendencia::find($request->id);
        
        if($pendencia!==null && !$pendencia->foi_resolvida){
            dd($pendencia);
            $pendencia->foi_resolvida=true;
            $pendencia->save();
            $transferencia=Transacao::find($pendencia->transacao_id);
            $emprestimo=Emprestimo::find($pendencia->emprestimo_id);
            if($request->aprovado){
             if($pendencia->tipo==='transferencia'){
                (New TransacaoController())->realizarTransferenciaPendente($transferencia);
             }
             else{
                (New EmprestimoController())->efetuar($emprestimo);
             }
        }
        else{
                if($pendencia->tipo==='transferencia'){
                   
                }
                else{
                  
                   $emprestimo->esta_pendente=false;
                   $emprestimo->foi_aprovado=true;
                  
                }   
        }
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
