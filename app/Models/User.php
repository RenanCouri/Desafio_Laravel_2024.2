<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'foto',
        'numero_telefone',
        'CPF',
        'data_nascimento',
        'cargo',
        'endereco_id',
        'usuario_responsavel_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
   public function users(){
        return $this->hasMany(User::class,'usuario_responsavel_id');
   }
   public function usuario_responsavel(){
    return $this->belongsTo(User::class,'usuario_responsavel_id');
   }
   public function conta(){
    return $this->hasOne(Conta::class,"user_id");
   }
   public function endereco(){
    return $this->belongsTo(Conta::class);
   }
   public function pendencias(){
    return $this->hasMany(Pendencia::class,"autoridade_id");
   }
   public function getUsuariosComuns(){
    $users=$this->users;
    $usuariosComuns=[];
    foreach($users as $user){
        if($user->cargo=='usuario_comum')
           $usuariosComuns[]=$user;
    }
    return $usuariosComuns;
    
   }
   public function getPendenciasNaoResolvidas(){
    $pendencias=$this->pendencias;
   
    $naoResolvidas=[];
    foreach ($pendencias as $pendencia){
        if(!$pendencia->foi_resolvida)
           $naoResolvidas[]=$pendencia;
    }
    return $naoResolvidas;
}
   public function getGerentes(){
    $users=$this->users;
    $gerentes=[];
    foreach($users as $user){
        if($user->cargo=='gerente')
           $gerentes[]=$user;
    }
    return $gerentes;
}
}
