@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="content">
        <div class="container-fluid">
            @include('partials.alerts')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <form method="post" role="form" action="{{ route('team.update', $team->id) }}" data-parsley-validate>
                            <div class="card-body">
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name">Nome</label>
                                    <input type="text" value="{{$team->name}}" class="form-control" id="name" name="name"
                                           placeholder="Nome">
                                    @if ($errors->has('name'))
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                    <label for="description">Descrição</label>
                                    <input type="text" value="{{$team->description}}" class="form-control" id="description" name="description"
                                           placeholder="Descrição">
                                    @if ($errors->has('description'))
                                        <p class="text-danger">{{ $errors->first('description') }}</p>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                    <label for="username">Username</label>
                                    <input type="text" value="{{$team->username}}" class="form-control" id="username" name="username"
                                           placeholder="Username">
                                    @if ($errors->has('username'))
                                        <p class="text-danger">{{ $errors->first('username') }}</p>
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
                                    <input type="password" value="{{ Request::old('password_confirmation') ?: '' }}"
                                           class="form-control" id="password_confirmation" name="password_confirmation"
                                           placeholder="Confirmação da Password">
                                    @if ($errors->has('password_confirmation'))
                                        <p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
                                    @endif
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
