@extends('layout')

@section('TituloCabecalho')
deposito_e_saque
@endsection
@section('titulo')
Depósito e Saque
@endsection
@section('conteudo')
<form method="post"  style="margin:auto 2% 5% 2%;"  onsubmit="return confirm('Tem certeza que deseja realizar tal ação??')">
@csrf  
<h5>Informe o tipo de ação que deseja fazer: </h5>
<select name="tipo"class="form-select mt-3" id="tipo">
            <option value="deposito">Depósito</option>
            <option value="saque">Saque</option>
        </select>
    <h5>Informe os dados da conta a qual será afetada pela ação: </h5>
<div class="form-group">
        
        <label for="agencia">Agência</label>
       <input type="text" name="agencia" id="agencia" required class="form-control">
       <label for="conta">Conta</label>
       <input type="text" name="conta" id="conta" required class="form-control">
       <label for="valor">Valor</label>
       <input type="number" name="valor" id="valor" required class="form-control">
       <label for="senha_alvo">Senha</label>
       <input type="text" name="senha_alvo" id="senha_alvo" required class="form-control">

</div>
<h5 style="margin-top:20px">Informe sua senha:</h5>
<div class="form-group">
<label for="senha_agente">Senha:</label>
       <input type="password" name="senha_agente" id="senha_agente" required class="form-control">
</div>
<button type="submit" class="btn btn-primary mt-2">Confirmar</button>
</form>
@endsection