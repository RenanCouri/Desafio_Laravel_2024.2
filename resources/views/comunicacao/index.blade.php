@extends('Layout')
@section('TituloCabecalho')
cominucacao_email
@endsection
@section('titulo')
Comunicação por e-mail
@endsection
@section('conteudo')
<form class="form mx-5" action="/enviar_email" method="post">
    @csrf
    <div style="border:2px dashed lightblue;border-radius:10px; padding:2px 5%; margin-bottom:14px ">
        <p  style="margin-left:-3%">Escolha para que grupo(s) será enviado o e-mail:</p>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="cargos_checagem[]" value="usuario_comum" id="usuario_comum">
            <label class="form-check-label" for="usuario_comum">
                Usuários Comuns
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="cargos_checagem[]" value="gerente" id="usuario_comum">
            <label class="form-check-label" for="gerente">
                Gerentes
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="cargos_checagem[]" value="administador" id="usuario_comum">
            <label class="form-check-label" for="administrador">
                Administradores
            </label>
        </div>
    </div>
    <div class="form-group mb-2">
        <label for="titulo_email">Título do email</label>
        <input type="text" id="titulo_email" name="titulo_email" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="conteudo_email">Conteúdo do e-mail</label>
        <textarea class="form-control" id="conteudo_email" name="conteudo_email" rows="3" required></textarea>
    </div>
    <button class="btn btn-primary mt-4" type="submit">Enviar</button>
</form>
@endsection