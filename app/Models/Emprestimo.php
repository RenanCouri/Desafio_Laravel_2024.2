<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emprestimo extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function Pendencia(){
        return $this->hasOne(Pendencia::class);
    }
    public function conta(){
        return $this->belongsTo(Conta::class);
    }
}
