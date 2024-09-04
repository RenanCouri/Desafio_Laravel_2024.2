@extends('layout')

@section('TituloCabecalho')
transferencia
@endsection
@section('titulo')
Transferência
@endsection
@section('conteudo')
<form method="post"  style="margin:auto 2% 5% 2%;"  onsubmit="return confirm('Tem certeza que deseja realizar tal ação??')">
@csrf  

<h5>Informe os seguintes dados para realizar a transferência: </h5>
<div class="form-group">
        
        <label for="agencia">Agência do destinatário</label>
       <input type="text" name="agencia" id="agencia" required class="form-control">
       <label for="conta">Conta do destinatário</label>
       <input type="text" name="conta" id="conta" required class="form-control">
       <label for="valor">Valor</label>
       <input type="number" name="valor" id="valor" required class="form-control">
       <label for="senha_alvo">Sua senha:</label>
       <input type="text" name="senha_alvo" id="senha_alvo" required class="form-control">

</div>

<button type="submit" class="btn btn-primary mt-2">Confirmar</button>
</form>
@endsection