<?php

use App\Models\Conta;
use Faker\Factory;

if (! function_exists('gerarSenhaLimiteSaldo')) {
    function gerarNumeroSenhaLimiteSaldo() {
        $faker=Factory::create(('pt_BR'));
        $senhas=$numeros=Conta::query()->get(['senha'])->toArray();
        $dados=[];
        do{
           $dados['senha']=$faker->numerify('######');

        }while(in_array($dados['senha'],$senhas));
        $dados['limite_transferencias']=(double)$faker->numberBetween(1000,3000000)/100;
        $dados['saldo']=(double)$faker->numberBetween(0,3000000)/100; 
        return $dados;
    }
}