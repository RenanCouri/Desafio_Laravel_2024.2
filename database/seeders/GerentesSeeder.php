<?php

namespace Database\Seeders;

use App\Models\Conta;
use Database\Factories\GerenteFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GerentesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gerentes=(new GerenteFactory())->count(10)->create();
        foreach($gerentes as $gerente){
            $dados=gerarNumeroSenhaLimiteSaldo();
            $dados['numero_agencia']= gerarNumeroAgencia();
            $dados['numero_conta']=gerarNumeroConta();
            $dados['user_id']=$gerente->id;
              Conta::create($dados); 
            $gerente->usuario_responsavel_id=$gerente->id;
            $gerente->save();
        }
    }
}
