<?php

use App\Models\Conta;
use Faker\Factory;

if (! function_exists('gerarNumeroAgencia')) {
    function gerarNumeroAgencia() {
        $faker=Factory::create(('pt_BR'));
        $agencias=Conta::query()->get(['numero_agencia'])->toArray();
        if($agencias==null || sizeof($agencias)==2)
           $num=$faker->numerify('####');
        else{
            $sorteio=$faker->numberBetween(0,sizeof($agencias));
            if($sorteio<=2)
               $num=$faker->numerify('####');
            else{
                $num=$faker->randomElement($agencias);
            }
        }
        return $num;
    }
}