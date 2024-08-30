@extends('layout')

@section('conteudo')
<a href="{{'/'}}@yield('retorno')"><div class="btn btn-primary mb-2 mx-2"><--</div></a>
<form method="post"  style="margin:auto 2% 5% 2%;"  >
@csrf    
        
<div class="form-group">
            <label for="name">Nome</label>
       <input type="text" name="name" id="name" required class="form-control">
        </div>
    
        <div class="form-group">
            <label for="email">E-mail</label>
       <input type="email" name="email" id="email" required class="form-control">
        </div>
        <div class="form-group">
            <label for="password">Senha</label>
            <input type="password" name="password" id="password" required min="1" class="form-control">
        </div>
       <div class="form-group">
            <label for="numero_cpf">CPF</label>
       <input type="text" name="numero_cpf" id="numero_cpf" required class="form-control">
        </div>
        <div class="form-group">
            <label for="numero_telefone">numero de telefone:</label>
       <input type="text" name="numero_telefone" id="numero_telefone" required class="form-control">
        </div>
        <div class="form-group">
            <label for="data_nascimento">data de nascimento:</label>
       <input type="date" name="data_nascimento" id="data_nascimento" required class="form-control">
        </div>
        <div class="form-group">
            <label for="pais">pais:</label>
            <input type="text" name="pais" id="pais" required class="form-control">
            <label for="estado">estado:</label>
            <input type="text" name="estado" id="estado" required class="form-control">
            <label for="cidade">cidade:</label>
            <input type="text" name="cidade" id="cidade" required class="form-control">
        </div>
        <div class="form-group">
            <label for="bairro">bairro:</label>
            <input type="text" name="bairro" id="bairro" required class="form-control">
            <label for="rua">rua:</label>
            <input type="text" name="rua" id="rua" required class="form-control">
            <label for="numero_predial">número:</label>
            <input type="text" name="numero_predial" id="numero_predial" required class="form-control">
            <label for="completemento">complemento:</label>
            <input type="text" name="completemento" id="completemento" required class="form-control">
        </div>
        @yield('parte_contas')
        <div class="mt-10">
        <button type="submit" class="btn btn-primary mt-3">Criar</button>
        </div>
       


</form>
@endsection     
      