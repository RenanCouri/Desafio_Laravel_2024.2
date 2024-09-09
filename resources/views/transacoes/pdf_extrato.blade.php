@extends('layout')

@section('TituloCabecalho')
extrato_bancario
@endsection
@section('titulo')
Extrato Bancário
@endsection
@section('conteudo')
<div class="d-flex justify-content-between px-5 py-3"><div style="font-weight:bold">Saldo: R$ {{number_format($conta->saldo,2,',','')}}</div>
<h2>Transações nos últimos {{$meses}} da conta</h2> 
<table class="table mx-2">      
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
            @for($transacoes as $transacao)
              <tr class="table-row">
                   <td>{{$transacao->created_at}}</td>
                   <td>{{$transacao->tipo}}</td>
                   <td>{{$transacao->contaRem->user->name}}</td>
                   <td>{{$transacao->contaDes->user->name}}</td>
                   <td>R$ {{number_format($transacao->valor,2,',','')}}</td>
          </tr>
             @endfor
        </tbody>
     </table>
@endsection  