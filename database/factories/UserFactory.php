<?php

namespace Database\Factories;

use App\Models\Endereco;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

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
        $gerentesPossiveis = User::where('cargo','gerente')->pluck('id')->toArray();
        return [
            'name' => $this->faker->name(),
            'email'=>  $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' =>  Hash::make('password'),
            'remember_token' => Str::random(10),
             'endereco_id' => Endereco::factory()->create()->id,   
              'numero_telefone' => $this->faker->phoneNumber(),  
               'data_nascimento' => $this->faker->dateTimeBetween('-120 years','-20 years'), 
                'cpf' => $this->faker->cpf(),
                'foto' => null,
                'usuario_responsavel_id' => fake()->randomElement($gerentesPossiveis),
            ];
        
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
