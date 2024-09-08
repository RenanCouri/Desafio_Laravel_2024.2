@extends('layout')
@section('TituloCabecalho')
Pendencias
@endsection
@section('titulo')
Pendências
@endsection
@section('conteudo')
@if($pendencias===null|| sizeOf($pendencias)===0)
<div class="alert alert-primary" style="text-align:center; width:fit-content; padding:15px; margin:auto auto">Você não tem pendências a serem resolvidas no momento</div>
@else
<ul style="list-style:none"class="list-group">
    <li style="border-bottom: 1px solid blue"></li>
@foreach($pendencias as $pendencia)
<li class="list-group-item" style="border-bottom: 1px solid blue; padding:5px">
  <ul class="list-group">
  <li class="list-group-item"><span style="font-weight:bold; color:darkblue; font-size:1.5em">Titulo : </span>{{$pendencia->titulo}}</li>
  <li class="list-group-item"><span style="font-weight:bold; color:darkblue; font-size:1.5em">Usuário : </span><a href="/verUsuarioComum/{{$pendencia->transacao->contaRem->user->id ?? $pendencia->emprestimo->conta->user->id}}">{{$pendencia->transacao->contaRem->user->name ?? $pendencia->emprestimo->conta->user->id}}</a></li>
  @if($pendencia->tipo==='transferencia')
  <li class="list-group-item"><span style="font-weight:bold; color:darkblue; font-size:1.5em">Limite : </span>R$ {{number_format($pendencia->transacao->contaRem->limite_transferencias,2,',','')}}</li>
  @endif
  <li class="list-group-item"><span style="font-weight:bold; color:darkblue; font-size:1.5em">Valor : </span>R$ 
  @if($pendencia->tipo==='transferencia')
  {{number_format($pendencia->transacao->valor,2,',','')}}
  @else
  {{number_format($pendencia->emprestimo->valor,2,',','')}}     
  @endif                      
  </li>
  <form style="max-width:10%" method="post">
    @csrf
    <input type="hidden" name="id" value="{{$pendencia->id}}">
    <select class="form-select mt-3"  name="aprovado" id="aprovado">
        <option type="number" value="1">Aprovar</option>
        <option type="number" value="0">Negar</option>
    </select>
    <button class="btn btn-primary mt-3">Confirmar</button>
  </form>
  </ul>
</li>
@endforeach
</ul>
@endif
@endsection