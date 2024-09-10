<?php

namespace Database\Seeders;

use App\Models\Conta;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuariosComunsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users=(new UserFactory())->count(30)->create();
        foreach($users as $user){
            $dados=gerarNumeroSenhaLimiteSaldo();
            $dados['numero_agencia']= gerarNumeroAgencia();
            $dados['numero_conta']=gerarNumeroConta();
            $dados['user_id']=$user->id;
              Conta::create($dados);  
        }
        
    }
}
