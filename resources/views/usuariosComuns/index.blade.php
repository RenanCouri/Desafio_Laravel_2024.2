@extends('layout')
@section('TituloCabecalho')
usuariosComuns
@endsection
@section('titulo')
Usuários Comuns
@endsection
@section('conteudo')
    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>email</th>
                <th>CPF</th>
                <th colspan="2">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
              <tr class="table-row">
                   <td>{{$user->name}}</td>
                   <td>{{$user->email}}</td>
                   <td>{{$user->CPF}}</td>
                   <td colpan="2" class="box">
                    <button>editar</button><button>ver</button><button>excluir</button>
                   </td>
              </tr>
             @endforeach 
        </tbody>
     </table>
@endsection