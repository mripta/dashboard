@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="content">
        <div class="container-fluid">
            @include('partials.alerts')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <form method="post" role="form" action="{{ route('teams.update', $team->id) }}" data-parsley-validate>
                            <div class="card-body">
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name">Nome</label>
                                    <input type="text" value="{{$team->name}}" class="form-control" id="name" name="name"
                                           placeholder="Nome" required>
                                    @if ($errors->has('name'))
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                    <label for="description">Descrição</label>
                                    <input type="text" value="{{$team->description}}" class="form-control" id="description" name="description"
                                           placeholder="Descrição" required>
                                    @if ($errors->has('description'))
                                        <p class="text-danger">{{ $errors->first('description') }}</p>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                    <label for="username">Username</label>
                                    <input type="text" value="{{$team->username}}" class="form-control" id="username" name="username"
                                           placeholder="Username" required>
                                    <small id="emailHelp" class="form-text text-muted">Estes dados serão utilizados para autenticação e envio de dados para o broker.</small>
                                    @if ($errors->has('username'))
                                        <p class="text-danger">{{ $errors->first('username') }}</p>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password">Nova Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                           placeholder="Password">
                                    @if ($errors->has('password'))
                                        <p class="text-danger">{{ $errors->first('password') }}</p>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <label for="password_confirmation">Confirmação da Nova Password</label>
                                    <input type="password" value="{{ old('password_confirmation') ?: '' }}"
                                           class="form-control" id="password_confirmation" name="password_confirmation"
                                           placeholder="Confirmação da Password">
                                    @if ($errors->has('password_confirmation'))
                                        <p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
                                    @endif
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="users">Utilizadores</label>
                                    <select class="select2" name="users[]" id="users" multiple="multiple" data-placeholder="Utilizadores com acesso ao Ponto de Recolha" style="width: 100%;">
                                    {{-- iterate users in team --}}
                                    @foreach($team->notOwners as $usero)
                                        <option value="{{$usero->id}}" selected>{{$usero->name}}</option>  
                                    @endforeach
                                    {{-- iterate users not in team --}}
                                    @foreach ($users as $usert)
                                        <option value="{{$usert->id}}">{{$usert->name}}</option> 
                                    @endforeach
                                    </select>
                                    <small id="emailHelp" class="form-text text-muted">Utilizadores com acesso ao Ponto de Recolha</small>
                                </div>
                            </div>
                            <div class="card-footer">
                                @csrf
                                @method('PATCH')
                                <button id="submit" type="submit" class="btn btn-success">Guardar</button>
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
    $('.select2admin').select2()
    
    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'dark-adminlte'
    })
});
@endsection