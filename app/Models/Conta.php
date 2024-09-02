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
        return $this->hasMany(Transacao::class);
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
}
