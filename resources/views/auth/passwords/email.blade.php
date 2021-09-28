@extends('layouts.login')

@section('content')
    <div class="card-body login-card-body">
        <p class="login-box-msg">Recuperar Palavra-Passe</p>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="input-group mb-3">
                <input type="email"  name="email" class="form-control @error('email') is-invalid @enderror"
                       placeholder="Email" value="{{ old('email') }}" required autocomplete="email" autofocus>
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
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Enviar Link de Recuperação</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <p class="mt-3 mb-1">
            <a href="{{ route('login') }}">Iniciar Sessão</a>
        </p>
    </div>
@endsection
