<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{
    use HasFactory;
    protected $table = 'transacoes'; //Coloco aqui devido ao plural irregular da palavra 'transação'.
    protected $fillable = ['valor','tipo'];
}
