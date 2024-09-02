<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emprestimo extends Model
{
    use HasFactory;
    protected $fillable = ['valor','taxa_juros_mensal','data_limite'];
    public function Pendencia(){
        return $this->hasOne(Pendencia::class);
    }
}
