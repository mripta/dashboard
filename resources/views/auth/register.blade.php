@extends('layouts.login')

@section('content')
    <div class="card-body login-card-body">
        <p class="login-box-msg">Criar Conta</p>
        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="input-group form-group mb-3">
                <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Nome" name="name" value="{{ old('name') }}" required autofocus>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="input-group form-group mb-3">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Endereço de E-mail" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" @if($email) readonly @endif>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="input-group mb-3">
                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Palavra-Passe" name="password" required autocomplete="new-password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="input-group mb-3">
                <input type="password" id="password-confirm" class="form-control @error('password-confirm') is-invalid @enderror" placeholder="Confirmação da Palavra-Passe" name="password_confirmation" required autocomplete="new-password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                @error('password-confirm')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="row">
                <div class="col-6">
                    <button type="submit" class="btn btn-primary btn-block">Registar</button>
                </div>
            </div>
        </form>
        <p class="mb-1">
            <a href="{{ route('login') }}">Iniciar Sessão</a>
        </p>
        <p class="mb-1">
            <a href="{{ route('password.request') }}">Recuperar palavra-Passe</a>
        </p>
@endsection