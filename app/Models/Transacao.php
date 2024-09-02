<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{
    use HasFactory;
    protected $table = 'transacoes'; //Coloco aqui devido ao plural irregular da palavra 'transação'.
    protected $guarded = [];
    public function contaRem(){
        return $this->belongsTo(Conta::class,'conta_remetente_id');
    }
    public function contaDes(){
        return $this->belongsTo(Conta::class,'conta_destinatario_id');
    }
    public function pendencia(){
        return $this->hasOne(Pendencia::class);
    }
}
