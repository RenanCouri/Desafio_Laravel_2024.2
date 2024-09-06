@extends('layout')

@section('TituloCabecalho')
emprestimo
@endsection
@section('titulo')
Seção de Empréstimo
@endsection
@section('conteudo')
@if($emprestimo !==null && $emprestimo->esta_pendente)
<h3 class="alert alert-secondary">Você já tem um empréstimo pendente de valor R$ {{number_format($emprestimo->valor,2,',','')}}. Aguarde o parecer do gerente.</h3>
@else
<form action="" method="post" class="mx-4" >
    @csrf
@if($emprestimo===null )
<h3>Insira os dados para realizar um novo empréstimo:</h3>
<div class="form-group" style="min-width:80px;width:35%;">
    <label for="valor">Valor do empréstimo: </label>
    <input type="number" class="form-control" name="valor" id="valor" required>
</div>
@else
<h3 class="mb-3">Você tem um empréstimo a pagar. Quite-o para poder realizar novos: </h3>
<div class="my-2"><span style="font-weight:bold">Divida atual : </span>R$ {{number_format($emprestimo->quantidade_a_pagar,2,',','')}}</div>
<input type="hidden" value="{{$emprestimo->id}}" name="emprestimo_id">
<div class="form-group"  style="min-width:80px;width:35%;">
    <label for="valor_a_pagar">Valor a pagar: </label>
    <input type="number" class="form-control" name="valor_a_pagar" id="valor_a_pagar" required>
</div>
@endif

<div class="form-group"  style="min-width:80px;width:35%;">
    <label for="valor_a_pagar">Senha: </label>
    <input type="number" class="form-control" name="senha" id="senha" required>
</div>
<button class="btn btn-primary mt-3" type="submit">@if($emprestimo===null)
    Solicitar empréstimo
    @else
    Enviar pagamento
    @endif
</button>
</form>
@endif
@endsection