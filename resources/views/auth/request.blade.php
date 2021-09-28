@extends('layouts.login')

@section('content')
    <div class="card-body register-card-body">
        <p class="login-box-msg">Efetuar Pedido de Registo</p>
        <form action="{{ route('invite.store') }}" method="POST">
            @csrf
            <div class="input-group form-group mb-3">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Endereço de E-mail" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
                <small id="emailHelp" class="form-text text-muted">O convite apenas será enviado depois de aprovado por um administrador.</small>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Enviar Pedido de Registo</button>
                </div>
            </div>
        </form>
        <p class="mt-3 mb-1">
            <a href="{{ route('login') }}">Iniciar Sessão</a>
        </p>
    </div>
        
@endsection
