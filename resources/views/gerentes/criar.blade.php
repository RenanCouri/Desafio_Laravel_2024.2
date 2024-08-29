@extends('formCriarBasico')

@section('TituloCabecalho')
criar_gerente
@endsection
@section('titulo')
Criar Gerente
@endsection
@section('parte_contas')
<div class="form-group">
            <label for="numero_agencia">agencia:</label>
            <input type="text" name="numero_agencia" id="numero_agencia" required class="form-control">
            <label for="numero_conta">conta:</label>
            <input type="text" name="numero_conta" id="numero_conta" required class="form-control">
            <label for="limite_transferencias">limite:</label>
            <input type="number" name="limite_transferencias" id="limite_transferencias" required class="form-control">
            <label for="senha">senha:</label>
            <input type="number" name="senha" id="senha" required class="form-control">
</div>
@endsection