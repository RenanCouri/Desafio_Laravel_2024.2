<?php

namespace App\Listeners;

use App\Events\Comunicacao;
use App\Mail\Comunicacao as MailComunicacao;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class EnviarEmailComunicacao
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Comunicacao $event): void
    {
        $dados=$event->dados;
        if(sizeof($dados)===3)
          $users= User::all();
        else if(sizeof($dados)===2)
         $users=User::query()->where('cargo',$dados[0])->orWhere('cargo',$dados[1]);
        else
          $users=User::query()->where('cargo',$dados[0]);
        
    $i=5;
    foreach($users as $user){
        
        $email= new MailComunicacao($dados);
       //Antigo jeito: (delay era feito no console) Mail::to($user)->queue($email); Implementaremos um novo:
       $quandoEnviar = now()->addSeconds($i);
       $i+=10;
       Mail::to($user)->later($quandoEnviar,$email);
        //sleep(5); Funciona como um modelo síncrono. Portanto, não atende mais aos nossos interesses.
        //Queremos agora um modelo assíncrono, no qual o usuário do site não terá de esperar todas os email serem enviados para continuar fazendo suas terefas.
    }
    }
}
