<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    use HasFactory;
    protected $fillable = ['numero_agencia','limite_transferencias','data_limite','senha','numero_conta','saldo','divida','user_id'	];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function transacoes(){
        return $this->hasMany(Transacao::class,'conta_remetente_id');
    }
    public function emprestimos(){
        return $this->hasMany(Emprestimo::class);
    }
    public function sacar($valor){
        if($valor<0 || $valor> $this->saldo)
           return false;
       
        $this->saldo-=$valor;
       
        $this->save();
        return true;
    }
    public function depositar($valor){
        if($valor<0)
           return false;
        
        $this->saldo+=$valor;
        
        $this->save();
        return true;
    }
    public function getEmprestimosNaoPagosOuPendentes()
    {
        $emprestimos=$this->emprestimos;
        $naoPago=null;
        foreach($emprestimos as $emprestimo)
        {
            if($emprestimo->esta_pendente ||($emprestimo->foi_aprovado && $emprestimo->quantidade_a_pagar>0) )
            {
                
               $naoPago=$emprestimo;
               break;
            }   
        }
        return $naoPago;
    }
    public function transacoesSelecionadas(){
        $transacoes=Transacao::query()->where('conta_remetente_id',$this->id)->orWhere('conta_destinatario_id',$this->id)->get();
       
        $selecionadas=[];
        foreach($transacoes as $transacao)
        {
           if(!$transacao->esta_pendente)
              $selecionadas[]=$transacao;
        }
        return $selecionadas;
    }

}
