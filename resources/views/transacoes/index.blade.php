@extends('layout')

@section('TituloCabecalho')
extrato_bancario
@endsection
@section('titulo')
Extrato Bancário
@endsection
@section('content')
<div class="d-flex justify-content-evenly"><div style="font-weight:bold">Saldo: R$ {{$saldo}}</div> <button class="btn btn-primary">Gerar relatório em PDF</button></div>
<table class="table mx-2">
      <caption>Tabela contendo últimas 10 transações da conta</caption>        
<thead>
            <tr>
                <th>Data</th>
                <th>Tipo</th>
                <th>Remetente</th>
                <th>Destinatário</th>
                <th>Valor</th>
                <th colspan="2">Ações</th>
            </tr>
        </thead>
        <tbody>
            @for($i=0; $i < 10 && $i < $transacoes.lenght ; $i++ )
              <tr class="table-row">
                   <td>{{$transacoes[i]->created_at}}</td>
                   <td>{{$transacoes[i]->tipo}}</td>
                   <td>{{$remetentes[i]}}</td>
                   <td>{{$destinarios[i]}}</td>
                   <td>R$ {{$transacoes[i]->valor}}</td>
          </tr>
             @endfor
        </tbody>
     </table>
@endsection     