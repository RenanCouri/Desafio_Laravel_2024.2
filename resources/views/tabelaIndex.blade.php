@extends('layout')

@section('conteudo')

@if($permissao)
<div class="d-flex p-3 justify-content-end">
    <a href="{{'/criar'}}@yield('nome_pagina')"><button class="btn btn-primary">Cadastrar Novo</button></a>
</div>
@endif
<table class="table mx-2">
    <thead>
        <tr>
            <th>Nome</th>
            <th>email</th>
            <th>CPF</th>
            <th colspan="2" style="text-align:center">Ações</th>
        </tr>
    </thead>
    <tbody>
       
        
     
        
        @foreach($users as $user)
        <tr class="table-row">
            <td>{{$user->name}} 
                @if($user->id===$atual)
                  <em> (Você)</em>
                @elseif(isset($admCriador) && $admCriador===$user->id)  
                <em>(Quem o cadastrou)</em>
                @endif  
            </td>

            <td>{{$user->email}}</td>
            <td>{{$user->CPF}}</td>
            @if(isset($admCriador) && $admCriador===$user->id) 
            <td style="text-align: center">Nenhuma ação disponível</td>
            @else
            <td class="d-flex flex-wrap gap-1 justify-content-center">
                <a href="{{'/editar'}}@yield('nome_pagina')/{{$user->id}}"><button class="btn btn-primary mx-2">Editar</button></a>
                <a href="{{'/ver'}}@yield('nome_pagina')/{{$user->id}}"><button class="btn btn-secondary mx-2">Ver</button></a>
                <form method="post" action="{{'/excluir'}}@yield('nome_pagina')"
                    onsubmit="return confirm('Tem certeza que deseja remover {{ addslashes($user->name) }}?')">
                    @csrf
                    <input type="hidden" value="{{$user->id}}" name="user_id">
                    <button type="submit" class="btn btn-danger mx-2">Excluir</button>
                </form>
            </td>
            @endif
        </tr>
        @endforeach
        
    </tbody>
</table>
<div style="width:50%;margin:auto">
 @if(!is_array($users))   
{{ $users->links() }}
@endif
</div>
@endsection