@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="content">
    <div class="container-fluid">
        @include('partials.alerts')
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <form method="post" role="form" action="{{ route('pontos.create') }}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name">Nome</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="Nome do Ponto de Recolha" value="{{ old('name') ?: '' }}">
                                @if ($errors->has('name'))
                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('desc') ? ' has-error' : '' }}">
                                <label for="desc">Descrição</label>
                                <input type="text" class="form-control" id="desc" name="desc"
                                       placeholder="Descrição do Ponto de Recolha" value="{{ old('desc') ?: '' }}">
                                @if ($errors->has('desc'))
                                    <p class="text-danger">{{ $errors->first('desc') }}</p>
                                @endif
                            </div>
                            <hr>
                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <label for="username">Username </label>
                                <input type="text" class="form-control" id="username" name="username"
                                       placeholder="Username do Ponto de Recolha" value="{{ old('username') ?: '' }}">
                                       <small id="emailHelp" class="form-text text-muted">Estes dados serão utilizados para autenticação e envio de dados para o broker.</small>
                                @if ($errors->has('username'))
                                    <p class="text-danger">{{ $errors->first('username') }}</p>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                       placeholder="Password do Ponto de Recolha" value="{{ old('password') ?: '' }}">
                                @if ($errors->has('password'))
                                    <p class="text-danger">{{ $errors->first('password') }}</p>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label for="password_confirmation">Confirmação da Password</label>
                                <input type="password" value="{{ old('password_confirmation') ?: '' }}" class="form-control" 
                                    id="password_confirmation" name="password_confirmation" placeholder="Confirmação da Password do Ponto de Recolha"
                                    value="{{ old('password_confirmation') ?: '' }}">
                                @if ($errors->has('password_confirmation'))
                                    <p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
                                @endif
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="users">Utilizadores</label>
                                <select class="select2" name="users[]" id="users" multiple="multiple" data-placeholder="Utilizadores com acesso ao Ponto de Recolha" style="width: 100%;">
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                                </select>
                                <small id="emailHelp" class="form-text text-muted">Utilizadores com acesso ao Ponto de Recolha</small>
                            </div>
                            <hr>
                            <div class="form-group{{ $errors->has('ref') ? ' has-error' : '' }}">
                                <label for="ref">Referências</label>
                                <div class="input-group increment">
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="ref" name="refs[]" placeholder="Referência"> 
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="refname" name="refsname[]" placeholder="Nome da Referência">
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-primary" id="add" type="button">Adicionar Linha</button>
                                    </div>
                                    <small id="emailHelp" class="form-text text-muted">As referências são utilizadas para identificar sensores</small>
                                </div>
                                @if ($errors->has('refs'))
                                    <p class="text-danger">{{ $errors->first('refs') }}</p>
                                @endif
                                @if ($errors->has('refs.*'))
                                    <p class="text-danger">{{ $errors->first('refs.*') }}</p>
                                @endif
                                @if ($errors->has('refsname'))
                                    <p class="text-danger">{{ $errors->first('refsname') }}</p>
                                @endif
                                @if ($errors->has('refsname.*'))
                                    <p class="text-danger">{{ $errors->first('refsname.*') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </div>
                    </form>
                    <div class="clone hide">
                        <div class="input-group" style="margin-top:5px">
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="ref" name="refs[]" placeholder="Referência"> 
                            </div>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="refname" name="refsname[]" placeholder="Nome da Referência">
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-danger" type="button">Remover Linha</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
$(document).ready(function() {
    // clone the ref texbox
    $("#add").click(function(){ 
        var html = $(".clone").html();
        $(".increment").after(html);
    });
    // delete the ref textbox
    $("body").on("click",".btn-danger",function(){ 
        $(this).parents(".input-group").remove();
    });

    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'dark-adminlte'
    })
});
@endsection