<?php

use App\Models\Conta;
use Faker\Factory;

if (! function_exists('gerarNumeroConta')) {
    function gerarNumeroConta() {
        $numeros=Conta::query()->get(['numero'])->toArray();
        $faker=Factory::create(('pt_BR'));
        $num=-1;
        do{
            $num=$faker->numerify('#######-#');
        } while(in_array($num,$numeros));

        return $num;
    }
}