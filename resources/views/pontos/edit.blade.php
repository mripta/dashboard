@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="content">
    <div class="container-fluid">
        @include('partials.alerts')
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <form method="post" role="form" action="{{ route('pontos.patch', $team->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="card-body">
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name">Nome</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="Nome do Ponto de Recolha" value="{{ $team->name }}">
                                @if ($errors->has('name'))
                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('desc') ? ' has-error' : '' }}">
                                <label for="desc">Descrição</label>
                                <input type="text" class="form-control" id="desc" name="desc"
                                       placeholder="Descrição do Ponto de Recolha" value="{{ $team->description }}">
                                @if ($errors->has('desc'))
                                    <p class="text-danger">{{ $errors->first('desc') }}</p>
                                @endif
                            </div>
                            <hr>
                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <label for="username">Username </label>
                                <input type="text" class="form-control" id="username" name="username"
                                       placeholder="Username do Ponto de Recolha" value="{{ $team->username }}">
                                       <small id="emailHelp" class="form-text text-muted">Estes dados serão utilizados para autenticação e envio de dados para o broker.</small>
                                @if ($errors->has('username'))
                                    <p class="text-danger">{{ $errors->first('username') }}</p>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                       placeholder="Password do Ponto de Recolha" value="">
                                @if ($errors->has('password'))
                                    <p class="text-danger">{{ $errors->first('password') }}</p>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label for="password_confirmation">Confirmação da Password</label>
                                <input type="password" value="{{ old('password_confirmation') ?: '' }}" class="form-control" 
                                    id="password_confirmation" name="password_confirmation" placeholder="Confirmação da Password do Ponto de Recolha"
                                    value="">
                                @if ($errors->has('password_confirmation'))
                                    <p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
                                @endif
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="users">Utilizadores</label>
                                <select class="select2" name="users[]" id="users" multiple="multiple" data-placeholder="Utilizadores com acesso ao Ponto de Recolha" style="width: 100%;">
                                @foreach($team->users as $selected)
                                    @foreach ($users as $user)
                                    <option value="{{$user->id}}" @if($user->id == $selected->id) selected @endif>{{$user->name}}</option>
                                    @endforeach
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
$(document).ready(function() {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'dark-adminlte'
    })
});
@endsection