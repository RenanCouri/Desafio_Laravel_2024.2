<?php

namespace Tests\Feature;

use App\Models\Conta;
use App\Models\User;
use Database\Factories\AdministradorFactory;
use Database\Factories\GerenteFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class GerenteTest extends TestCase
{
    use DatabaseMigrations;
    
    /**@test */
    public function test_criar_autenticado(): void
    {
        
        $admin = (new AdministradorFactory)->create();
        Auth::login($admin);
        
        $response = $this->actingAs($admin)->post('/criarGerente',[
            'name' => "Xenofonte",
            'email' => "xenofonte@atenas.hisotria",
            'numero_cpf' => "132.402.902-30",
            'foto' =>  UploadedFile::fake()->create('imagem.png'),
            'password' => "12345678abc",
            'pais' => "Brasil",
            'estado' => "Rio Grande Do Sul",
            'cidade' => "Erchia",
            'bairro' => "Polis Demo",
            'rua' => "Cavalo de Troia",
            'numero_predial' => 1090,
            'completemento' => 130,
            'numero_telefone'=> "0329889705312",
            'data_nascimento' => "1926-02-12",
        ]);
        $response->assertFound();
        $response->assertSessionHas('sucesso');
        $this->assertDatabaseHas('users', ['name' => 'Xenofonte']); 
    }
    /**@test */
    public function test_excluir_autenticado(): void
    {
        
        $admin = (new AdministradorFactory())->create();
        Auth::login($admin);
        $gerente =(new GerenteFactory())->create();
        $gerente->usuario_responsavel_id=$gerente->id;
        $id=$gerente->id;
        $gerente->save();
        $dados=gerarNumeroSenhaLimiteSaldo();
            $dados['numero_agencia']= gerarNumeroAgencia();
            $dados['numero_conta']=gerarNumeroConta();
            $dados['user_id']=$gerente->id;
              Conta::create($dados); 
        $response = $this->actingAs($admin)->post('/excluirGerente',[
            'user_id' => $gerente->id,
            
        ]);

        $response->assertFound();
        $response->assertSessionHas('sucesso');
        $this->assertDatabaseMissing('users',['id'=>$id]);
    }
    /**@test */
    public function test_ler_autenticado(): void
    {
        
        $admin = (new AdministradorFactory())->create();
        Auth::login($admin);
        $gerente =(new GerenteFactory())->create();
        $gerente->usuario_responsavel_id=$gerente->id;
        $gerente->save();
        $dados=gerarNumeroSenhaLimiteSaldo();
            $dados['numero_agencia']= gerarNumeroAgencia();
            $dados['numero_conta']=gerarNumeroConta();
            $dados['user_id']=$gerente->id;
              Conta::create($dados); 
        $response = $this->actingAs($admin)->get("/verGerente/{$gerente->id}");

        $response->assertSuccessful();
    }
    /**@test */
    public function test_editar_autenticado(): void
    {
        
        $admin = (new AdministradorFactory())->create();
        Auth::login($admin);
        $gerente=(new GerenteFactory())->create();
        $email=$gerente->email;
        $response = $this->actingAs($admin)->post('/editarGerente',[
            'user_id'=>$gerente->id,
            'name' => "Tardok",
            'email' => "tarkioka@t.c",
            'numero_cpf' => "132.402.902-30",
            'foto' =>  UploadedFile::fake()->create('imagem.png'),
            'password' => "12345678abc",
            'pais' => "Brasil",
            'estado' => "Rio Grande Do Sul",
            'cidade' => "Erchia",
            'bairro' => "Polis Demo",
            'rua' => "Cavalo de Troia",
            'numero_predial' => 1090,
            'completemento' => 130,
            'numero_telefone'=> "0329889705312",
            'data_nascimento' => "1926-02-12",
        ]);
        $response->assertFound();
        $response->assertSessionHas('sucesso');
        $this->assertDatabaseHas('users', ['email' => 'tarkioka@t.c']);
        $this->assertDatabaseMissing('users', ['email' => $gerente->email]);
    }
    public function test_criar_nao_autenticado(): void
    {
        
        $user = (new UserFactory)->create();
        Auth::login($user);
        
        $response = $this->actingAs($user)->post('/criarGerente',[
            'name' => "Xenofonte",
            'email' => "xenofonte@atenas.hisotria",
            'numero_cpf' => "132.402.902-30",
            'foto' =>  UploadedFile::fake()->create('imagem.png'),
            'password' => "12345678abc",
            'pais' => "Brasil",
            'estado' => "Rio Grande Do Sul",
            'cidade' => "Erchia",
            'bairro' => "Polis Demo",
            'rua' => "Cavalo de Troia",
            'numero_predial' => 1090,
            'completemento' => 130,
            'numero_telefone'=> "0329889705312",
            'data_nascimento' => "1926-02-12",
        ]);
        $response->assertFound();
        $response->assertSessionHas('sucesso');
        $this->assertDatabaseHas('users', ['name' => 'Xenofonte']); 
    }
    /**@test */
    public function test_excluir_nao_autenticado(): void
    {
        (new GerenteFactory())->create();
        $user = (new UserFactory)->create();
        Auth::login($user);
        $gerente =(new GerenteFactory())->create();
        $gerente->usuario_responsavel_id=$gerente->id;
        $id=$gerente->id;
        $gerente->save();
        $dados=gerarNumeroSenhaLimiteSaldo();
            $dados['numero_agencia']= gerarNumeroAgencia();
            $dados['numero_conta']=gerarNumeroConta();
            $dados['user_id']=$gerente->id;
              Conta::create($dados); 
        $response = $this->actingAs($user)->post('/excluirGerente',[
            'user_id' => $gerente->id,
            
        ]);

        $response->assertFound();
        $response->assertSessionHas('sucesso');
        $this->assertDatabaseMissing('users',['id'=>$id]);
    }
    /**@test */
    public function test_ler_nao_autenticado(): void
    {
        (new GerenteFactory())->create();
        $user = (new UserFactory)->create();
        Auth::login($user);
        $gerente =(new GerenteFactory())->create();
        $gerente->usuario_responsavel_id=$gerente->id;
        $gerente->save();
        $dados=gerarNumeroSenhaLimiteSaldo();
            $dados['numero_agencia']= gerarNumeroAgencia();
            $dados['numero_conta']=gerarNumeroConta();
            $dados['user_id']=$gerente->id;
              Conta::create($dados); 
        $response = $this->actingAs($user)->get("/verGerente/{$gerente->id}");

        $response->assertSuccessful();
    }
    /**@test */
    public function test_editar_nao_autenticado(): void
    {
        (new GerenteFactory())->create();
        $user = (new UserFactory)->create();
        Auth::login($user);
        $gerente=(new GerenteFactory())->create();
        $email=$gerente->email;
        $response = $this->actingAs($user)->post('/editarGerente',[
            'user_id'=>$gerente->id,
            'name' => "Tardok",
            'email' => "tarkioka@t.c",
            'numero_cpf' => "132.402.902-30",
            'foto' =>  UploadedFile::fake()->create('imagem.png'),
            'password' => "12345678abc",
            'pais' => "Brasil",
            'estado' => "Rio Grande Do Sul",
            'cidade' => "Erchia",
            'bairro' => "Polis Demo",
            'rua' => "Cavalo de Troia",
            'numero_predial' => 1090,
            'completemento' => 130,
            'numero_telefone'=> "0329889705312",
            'data_nascimento' => "1926-02-12",
        ]);
        $response->assertFound();
        $response->assertSessionHas('sucesso');
        $this->assertDatabaseHas('users', ['email' => 'tarkioka@t.c']);
        $this->assertDatabaseMissing('users', ['email' => $gerente->email]);
    }

    
}


