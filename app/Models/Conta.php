<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    use HasFactory;
    protected $fillable = ['numero_agencia','limite_transferencias','data_limite','senha','numero_conta','saldo','divida'	];
}
