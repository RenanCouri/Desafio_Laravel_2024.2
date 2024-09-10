@extends('layout')
@section('adicoes_cabecalho')
<style>
    nav{
        display:none !important;
    }
</style>
@endsection
@section('TituloCabecalho')
extrato_bancario
@endsection
@section('titulo')
Extrato Bancário
@endsection
@section('conteudo')
<div class="d-flex justify-content-between px-5 py-3">
<div style="font-weight:bold;font-size:1.2em">Banco: El Grand Banko</div>
    <div style="font-weight:bold;font-size:1.2em">Saldo: R$ {{number_format($conta->saldo,2,',','')}}</div>
<div style="font-weight:bold;font-size:1.2em">Usuário: {{$conta->user->name}}</div>
<div style="font-weight:bold;font-size:1.2em"> Data de emissão do relatório: {{$emitido}}</div> 
<div style="font-weight:bold;font-size:1.2em"> Período selecionado: últimos {{$meses}} meses</div> 
</div>

<table class="table mx-2">      
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
            @foreach($transacoes as $transacao)
              <tr class="table-row">
                   <td>{{$transacao->created_at}}</td>
                   <td>{{$transacao->tipo}}</td>
                   <td>@if($transacao->contaRem==null)
                   'Conta deletada'
                   @else
                    {{$transacao->contaRem->user->name }}
                    @endif</td>
                    <td>@if($transacao->contaDes==null)
                   'Conta deletada'
                   @else
                    {{$transacao->contaDes->user->name }}
                @endif</td>
                   <td>R$ {{number_format($transacao->valor,2,',','')}}</td>
          </tr>
             @endforeach
        </tbody>
     </table>
@endsection  