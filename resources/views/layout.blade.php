<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('TituloCabecalho')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
     @yield('adicoes_cabecalho')
</head>
<body>
     <nav style="background-color:lightblue" class="navbar navbar-light p-3">
         <div class="navbar-text">Navegação</div>
        <a class="navbar-brand" href="/dashboard2">Dashboard</a>
        <a class="navbar-brand" href="/usuariosComuns">Tabela Usuários Comuns</a>
     </nav>
    <main>
    <div  style="text-align:center">
            <h1 class="display-2" style="font-weight:bold; margin-bottom:35px;" >@yield('titulo')</h1>
            <p class="lead">@yield('paragrafoIntroducao')</p>
    </div>
    @if(session('sucesso') !== null)
    <div class="alert alert-success">
         {{session('sucesso')}}    
</div>    
@endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif           
        </div>
        @yield('conteudo')
        </div>
    </main>  
</body>
</html> 