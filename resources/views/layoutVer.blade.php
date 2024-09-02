@extends('Layout')
@section('adicoes_cabecalho')
<style>
    div>div{
        background-color: lightblue;
        border-top: 2px solid rgb(102,105,105);
       
    }
    main{
        background-color: rgb(220, 220, 220);
    }
    div>div:nth-last-of-type(1){
        border-bottom: 2px solid rgb(102,105,105);
    }
    
    html{
        background-color: rgb(220, 220, 220);
    }
    h1~p{
        background-color: rgb(220, 220, 190);
    }
    p{
        font-weight: bold;
    }
    span{
        font-weight:500;
        color:darkgreen;
    }
    
    
</style>
@endsection
@section('conteudo')
       <a href="{{'/'}}@yield('retorno')"><div class="btn btn-primary mb-2 mx-2"><--</div></a>
       <div class="p-3 " style="background-color:lightblue">
       <div class=row >   
       <p class="col-sm-6" >Nome:   <span>{{$user->name}}</span> </p>
              
        
    
        
            <p class="col-sm-6">E-mail:   <span>{{$user->email}}</span> </p>
            
         
       
            
       
        </div>  
        <div class=row > 
        <p class="col-sm-6">CPF:    <span>{{$user->CPF}}</span> </p>
            <p class="col-sm-6">Número de telefone:    <span>{{$user->numero_telefone}}</span> </p>
       
        
        
            
       
        </div>
        <div class=row >
        <p class="col-sm-6">data de nascimento:     <span>{{$user->data_nascimento}}</span> </p>
            <p class="col-sm-6">país:     <span>{{$endereco->pais}}</span> </p>
            
            
        </div>   
        
        <div class=row >
        <p class="col-sm-6">estado:       <span>{{$endereco->estado}}</span> </p>
            
            <p class="col-sm-6">cidade:     <span>{{$endereco->cidade}}</span> </p>
        </div> 
          <div class=row >   
            <p class="col-sm-6">bairro:      <span>{{$endereco->bairro}}</span> </p>
            
            <p class="col-sm-6">rua:         <span>{{$endereco->rua}}</span> </p>
          </div>  
          <div class=row > 
            <p class="col-sm-6">número:        <span>{{$endereco->numero_predial}}</span> </p>
            <p class="col-sm-6">complemento: 
            @if($endereco->completemento!==null)
                   <span>{{$endereco->completemento}}</span> 
            
            @else
               -
               @endif
            </p>
          </div>
        @if($user->cargo !== 'administrador')
        <div class=row >  
            <p class="col-sm-6">agencia:   <span>{{$conta->numero_agencia}}</span></p>
             
            <p class="col-sm-6">conta:  <span> {{$conta->numero_conta}}</span></p>
        </div>
        <div class=row >     
            <p class="col-sm-6">limite:   <span>{{$conta->limite_transferencias}}</span></p>
            
            <p class="col-sm-6">senha: <span> {{$conta->senha}}</span></p>
        </div>    
       
        @endif
        </div>
@endsection