@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="content">
        <div class="container-fluid">
            @include('partials.backend.alerts')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <form method="post" role="form" action="{{ route('definicoes.update') }}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Palavra-passe atual</label>
                                    <input type="password" value="{{ old('current_password') ?: '' }}" class="form-control{{ $errors->has('current_password') ? ' is-invalid' : '' }}" id="current_password"
                                           name="current_password" placeholder="Preencher Palavra-Passe Anterior">
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
                                <button id="submit" type="submit" class="btn btn-success">Alterar Palavra-passe</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
