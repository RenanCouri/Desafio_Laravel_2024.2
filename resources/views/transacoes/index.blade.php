@extends('layout')

@section('TituloCabecalho')
extrato_bancario
@endsection
@section('titulo')
Extrato Bancário
@endsection
@section('conteudo')
<div class="d-flex justify-content-between px-5 py-3"><div style="font-weight:bold">Saldo: R$ {{number_format($conta->saldo,2,',','')}}</div> 
<form action="/pdf_extrato" method="post" class="form">
    @csrf
    <select class="form-select mt-3"  name="aprovado" id="aprovado">
        <option type="number" value="1">Últimos 3 meses</option>
        <option type="number" value="0">Últimos 6 meses</option>
    </select>

    <button type="submit" class="btn btn-primary">Gerar relatório em PDF</button></div>
</form>

<table class="table mx-2">
      <caption>Tabela contendo últimas 10 transações da conta</caption>        
<thead>
            <tr>
                <th>Data</th>
                <th>Tipo</th>
                <th>Remetente</th>
                <th>Destinatário</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @for($i=0; $i < 10 && $i < sizeOf($transacoes) ; $i++ )
              <tr class="table-row">
                   <td>{{$transacoes[$i]->created_at}}</td>
                   <td>{{$transacoes[$i]->tipo}}</td>
                   <td>{{$transacoes[$i]->contaRem->user->name}}</td>
                   <td>{{$transacoes[$i]->contaDes->user->name}}</td>
                   <td>R$ {{number_format($transacoes[$i]->valor,2,',','')}}</td>
          </tr>
             @endfor
        </tbody>
     </table>
@endsection     