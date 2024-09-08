<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('TituloCabecalho')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
     @yield('adicoes_cabecalho')
     <style>
        li{
            background-color:lightblue !important; 
            border-radius:5px;
            border:2px solid blue !important;
            margin-top:18px;
            font-size:1.4em;
        }
     </style>
</head>
<body>
 <nav class="navbar d-flex justify-content-between p-3" style="background-color:lightblue"> 
 <div class="navbar-text" style="font-size:1.5em;font-weight:bold">Dashboard</div>
<form method="POST" class=""  action="http://127.0.0.1:8000/logout" onsubmit="return confirm('Tem certeza que deseja se deslogar?')">
    @csrf
    
     <a class="navbar-brand btn btn-danger" href="http://127.0.0.1:8000/logout" onclick="event.preventDefault();
        this.closest('form').submit();">Log Out</a>
</form>
</nav>
    <h2 style="text-align:center ; padding:2px; background-color:rgb(100,175,220)">Bem vindo ao sistema! Aqui estão as páginas que por você podem ser acessadas: </h2> 
    <ul style="list-style:none; flex-direction:row;margin:40px auto;width:90%;gap:5%" class="list-group flex-direction-row flex-wrap justify-content-around">
        <li class="list-group-item">
          
        <a class="navbar-brand" href="/usuariosComuns">Página de Usuários Comuns</a>
        </li>
    @yield('conteudo')
   </ul>
   
   
</body>