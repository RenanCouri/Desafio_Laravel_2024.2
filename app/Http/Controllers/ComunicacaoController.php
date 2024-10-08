<?php

namespace App\Http\Controllers;

use App\Events\Comunicacao as EventsComunicacao;
use App\Http\Requests\EmailRequest;
use App\Mail\Comunicacao;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ComunicacaoController extends Controller
{
    public function index()  {
        return view('comunicacao.index');
    }
    public function enviar(EmailRequest $request)  {
        
        $cargos=$request->cargos_checagem;

        event(new EventsComunicacao(['nome'=>$request->user()->name,
        'email'=>$request->user()->email,
        'assunto'=>$request->titulo_email,
        'conteudo'=>$request->conteudo_email,
         'cargos'=>$cargos] ));

         return redirect()->back();
    }

}
