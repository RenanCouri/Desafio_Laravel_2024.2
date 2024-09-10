@extends('layout')

@section('TituloCabecalho')
extrato_bancario
@endsection
@section('titulo')
Extrato Bancário
@endsection
@section('conteudo')
<div class="d-flex justify-content-between px-5 py-3"><div style="font-weight:bold;font-size:1.2em">Saldo: R$ {{number_format($conta->saldo,2,',','')}}</div> 
<form action="/pdf_extrato" method="post" class="form">
    @csrf
    <button type="submit" class="btn btn-primary">Gerar relatório em PDF</button>
    <select class="form-select mt-3"  name="data" id="data" required>
        <option type="number" value="0">Últimos 3 meses</option>
        <option type="number" value="1">Últimos 6 meses</option>
    </select>
</form>
</div>
<table class="table mx-2">
      <caption>Tabela contendo últimas 10 transações da conta</caption>        
<thead>
            <tr>
                <th>Data e hora</th>
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
                   <td>@if($transacoes[$i]->contaRem==null)
                   'Conta deletada'
                   @else
                    {{$transacoes[$i]->contaRem->user->name }}
                    @endif</td>
                    <td>@if($transacoes[$i]->contaDes==null)
                   'Conta deletada'
                   @else
                    {{$transacoes[$i]->contaDes->user->name }}
                @endif</td>
                   <td>R$ {{number_format($transacoes[$i]->valor,2,',','')}}</td>
          </tr>
             @endfor
        </tbody>
     </table>
@endsection     