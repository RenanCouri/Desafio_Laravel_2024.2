@extends('Layout')
@section('conteudo')

       
            <p >Nome:   <span>{{$user->name}}</span> </p>
              
        
    
        
            <p >E-mail:   <span>{{$user->email}}</span> </p>
            
            <p >Senha:   <span>{{$user->password}}</span> </p>
       
            <p >CPF:    <span>{{$user->CPF}}</span> </p>
       
        
        
            <p >Número de telefone:    <span>{{$user->numero_telefone}}</span> </p>
       
        
        
            <p>data de nascimento:     <span>{{$user->data_nascimento}}</span> </p>
       
        
        
            <p >país:     <span>{{$endereco->pais}}</span> </p>
            
            <p >estado:       <span>{{$endereco->estado}}</span> </p>
            
            <p >cidade:     <span>{{$endereco->cidade}}</span> </p>
            
        
        
            <p >bairro:      <span>{{$endereco->bairro}}</span> </p>
            
            <p >rua:         <span>{{$endereco->rua}}</span> </p>
            
            <p >número:        <span>{{$endereco->numero_predial}}</span> </p>
            @if($endereco->complemento!==null)
            <p >complemento:        <span>{{$endereco->complemento}}</span> </p>
            @endif
        
        @if($user->cargo !== 'administrador')
       
            <p >agencia:   {{$conta->numero_agencia}}</p>
             
            <p >conta:   {{$conta->numero_conta}}</p>
            
            <p >limite:   {{$conta->limite_transferencias}}</p>
            
            <p >senha:  {{$conta->senha}}</p>
            
       
        @endif

@endsection