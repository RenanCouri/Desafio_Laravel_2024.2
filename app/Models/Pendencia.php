<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendencia extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function transacao(){
        return $this->belongsTo(Transacao::class,'transacao_id');
    }
    public function emprestimo(){
        return $this->belongsTo(Emprestimo::class,'emprestimo_id');
    }
}
