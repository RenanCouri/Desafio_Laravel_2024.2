<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
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
        if($request->user()->conta->senha===$request->senha){
              
        }
    }
    public function efetuar(Emprestimo $emprestimo)
    {
                $emprestimo->esta_pendente=false;
                $emprestimo->foi_aprovado=true;
                $emprestimo->qtd_a_pagar=$emprestimo->valor;
                $conta=Conta::find($emprestimo->conta_id);
                $conta->depositar($emprestimo->valor);
                $emprestimo->save();
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
