<?php

namespace Database\Factories;

use App\Models\Endereco;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class GerenteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model=User::class;
    public function withFaker()
    {
        return FakerFactory::create('pt_BR');
    }
    public function definition(): array
    {
        $imagemReal= 'imagens/'.$this->faker->image('public/storage/imagens',300,270,null,false,true);
        return [
            'name' => $this->faker->name(),
            'email'=>  $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
            'endereco_id' => Endereco::factory()->create()->id,
            'numero_telefone' => $this->faker->phoneNumber(),
            'data_nascimento' => $this->faker->dateTimeBetween('-120 years','-20 years'),
            'cpf' => $this->faker->cpf(),
            'foto' => $imagemReal,
            'usuario_responsavel_id' => 1,
            'cargo' => 'gerente'
        ];
    }
}
