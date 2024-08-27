<?php

namespace App\Http\Controllers;

use App\Models\Pendencia;
use Illuminate\Http\Request;

class PendenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $userId)
    {
        $pendencias= Pendencia::query()->orderBy('created_at')->get();
        return view('pendencias.index',compact('pendencias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
