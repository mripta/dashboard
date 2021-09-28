@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="content">
        <div class="container-fluid">
            @include('partials.alerts')
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-pencil-alt"></i> Modificar Perfil</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="post" role="form" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="avatar">Avatar</label><br>
                                    <img class="image rounded-circle" src="{{asset('/img/profiles/'.Auth::user()->image)}}" alt="avatar" style="width: 80px;height: 80px; padding: 10px; margin: 0px; ">
                                    <input type="file" name="avatar" id="avatar" accept="image/png, image/jpeg" aria-describedby="fileHelp">
                                    <small id="fileHelp" class="form-text text-muted">O ficheiro deve ser uma imagem válida do tipo jpeg, jpg ou png menor que 2MB.</small>
                                </div>
                                <div class="form-group">
                                    <label for="name">Nome</label>
                                    <input type="text" value="{{ old('name') ?: $user->name }}" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name"
                                           name="name" placeholder="Preencher Nome" required>
                                    @if ($errors->has('name'))
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="name">Biografia</label>
                                    <input type="text" value="{{ old('bio') ?: $user->bio }}" class="form-control{{ $errors->has('bio') ? ' is-invalid' : '' }}" id="bio"
                                           name="bio" placeholder="Preencher Biografia">
                                    @if ($errors->has('bio'))
                                        <p class="text-danger">{{ $errors->first('bio') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="name">E-mail</label>
                                    <input type="email" value="{{ old('email') ?: $user->email }}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email"
                                           name="email" placeholder="Preencher E-mail" required>
                                    @if ($errors->has('email'))
                                        <p class="text-danger">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                @csrf
                                @method('PATCH')
                                <button id="submit" type="submit" class="btn btn-success">Modificar Perfil</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-key"></i> Atualizar Palavra-Passe</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="post" role="form" action="{{ route('profile.password') }}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Palavra-passe atual</label>
                                    <input type="password" value="{{ old('current_password') ?: '' }}" class="form-control{{ $errors->has('current_password') ? ' is-invalid' : '' }}" id="current_password"
                                           name="current_password" placeholder="Preencher Palavra-Passe Atual">
                                    @if ($errors->has('current_password'))
                                        <p class="text-danger">{{ $errors->first('current_password') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="name">Nova Palavra-passe</label>
                                    <input type="password" value="{{ old('new_password') ?: '' }}" class="form-control{{ $errors->has('new_password') ? ' is-invalid' : '' }}" id="new_password"
                                           name="new_password" placeholder="Preencher Nova Palavra-Passe">
                                    @if ($errors->has('new_password'))
                                        <p class="text-danger">{{ $errors->first('new_password') }}</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="name">Confirmação da Palavra-passe</label>
                                    <input type="password" value="{{ old('new_confirm_password') ?: '' }}" class="form-control{{ $errors->has('new_confirm_password') ? ' is-invalid' : '' }}" id="new_confirm_password"
                                           name="new_confirm_password" placeholder="Preencher Confirmação da Nova Palavra-Passe">
                                    @if ($errors->has('new_confirm_password'))
                                        <p class="text-danger">{{ $errors->first('new_confirm_password') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                @csrf
                                @method('PATCH')
                                <button id="submit" type="submit" class="btn btn-success">Atualizar Palavra-passe</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
@endsection
