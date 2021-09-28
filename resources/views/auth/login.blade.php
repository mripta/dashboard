@extends('layouts.login')

@section('content')
    <div class="card-body login-card-body">
        <p class="login-box-msg">Iniciar Sessão</p>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="input-group form-group mb-3">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Endereço de E-mail" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
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
                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Palavra-Passe" name="password" required autocomplete="current-password">
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
            <div class="row">
                <div class="col-6">
                    <div class="icheck-primary">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Lembrar-me</label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-6">
                    <button type="submit" class="btn btn-primary btn-block">Iniciar Sessão</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <p class="mb-1">
            <a href="{{ route('password.request') }}">Recuperar palavra-Passe</a>
        </p>
        <p class="mb-1">
            <a href="{{ route('invite.request') }}">Efetuar pedido de Registo</a>
        </p>
@endsection
