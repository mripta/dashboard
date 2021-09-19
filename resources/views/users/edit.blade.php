@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="content">
        <div class="container-fluid">
            @include('partials.alerts')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <form method="post" role="form" action="{{ route('users.update', $user->id) }}" data-parsley-validate>
                            <div class="card-body">
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name">Nome</label>
                                    <input type="text" value="{{$user->name}}" class="form-control" id="name" name="name"
                                           placeholder="Nome" required>
                                    @if ($errors->has('name'))
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email">E-mail</label>
                                    <input type="email" value="{{$user->email}}" class="form-control" id="email" name="email"
                                           placeholder="E-mail" required>
                                    @if ($errors->has('email'))
                                        <p class="text-danger">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('bio') ? ' has-error' : '' }}">
                                    <label for="bio">Biografia</label>
                                    <input type="text" value="{{$user->bio}}" class="form-control" id="bio" name="bio"
                                           placeholder="Biografia">
                                    @if ($errors->has('bio'))
                                        <p class="text-danger">{{ $errors->first('bio') }}</p>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                           placeholder="Password">
                                    @if ($errors->has('password'))
                                        <p class="text-danger">{{ $errors->first('password') }}</p>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <label for="password_confirmation">Confirmação da Password</label>
                                    <input type="password" value="{{ old('password_confirmation') ?: '' }}"
                                           class="form-control" id="password_confirmation" name="password_confirmation"
                                           placeholder="Confirmação da Password">
                                    @if ($errors->has('password_confirmation'))
                                        <p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
                                    @endif
                                </div>

                                <div class="form-check{{ $errors->has('admin') ? ' has-error' : '' }}">
                                    <input type="checkbox"class="form-check-input" id="admin" name="admin" @if($user->admin) checked @endif>
                                    <label class="form-check-label" for="admin">Administrador</label>
                                    <small id="emailHelp" class="form-text text-muted">Define o estado do utilizador como administrador da plataforma</small>
                                    @if ($errors->has('admin'))
                                        <p class="text-danger">{{ $errors->first('admin') }}</p>
                                    @endif
                                </div>

                                <hr>

                                <div class="form-group{{ $errors->has('teamadmin') ? ' has-error' : '' }}">
                                    <label for="password_confirmation">Administração de Grupos</label>

                                    @foreach($user->teams as $team)
                                    <div class="form-check{{ $errors->has('teamadmin') ? ' has-error' : '' }}">
                                        <input type="checkbox" class="form-check-input" id="teamadmin" name="teamadmin[{{$team->id}}]" @if($team->pivot->owner) checked @endif>
                                        <label class="form-check-label" for="teamadmin">Administrador do Ponto de Recolha {{$team->name}}</label>
                                        @if ($errors->has('teamadmin'))
                                            <p class="text-danger">{{ $errors->first('teamadmin') }}</p>
                                        @endif
                                    </div>
                                    @endforeach
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
