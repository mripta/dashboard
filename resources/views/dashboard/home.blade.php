@extends('layouts.admin')

@section('title', $title)

@section('content')
    <section class="content">
        <div class="container-fluid">
            @include('partials.alerts')
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{{ $usersc }}}</h3>
                            <p>@if ($usersc  == 1) Utilizador @else Utilizadores @endif</p>
                        </div>
                        <div class="icon">
                            <i class="fas @if ($usersc  == 1)fa-user @else fa-users @endif"></i>
                        </div>
                        @if (Auth::user()->isAdmin())<a href="{{ route('user.index') }}" class="small-box-footer">Explorar <i class="fas fa-arrow-circle-right"></i></a>@endif
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{{ $rinvc }}}</h3>
                            <p>@if ($rinvc == 1) Pedido @else Pedidos @endif de Convite</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        @if (Auth::user()->isAdmin())<a href="{{ route('admin.invites') }}" class="small-box-footer">Explorar <i class="fas fa-arrow-circle-right"></i></a>@endif
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{{ $pinvc }}}</h3>
                            <p>@if ($pinvc == 1) Convite Pendente @else Convites Pendentes @endif</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        @if (Auth::user()->isAdmin())<a href="{{ route('admin.invites') }}" class="small-box-footer">Explorar <i class="fas fa-arrow-circle-right"></i></a>@endif
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-olive">
                        <div class="inner">
                            <h3>{{{ $uinvc }}}</h3>
                            <p>@if ($uinvc == 1) Convite Aceite @else Convites Aceites @endif</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-envelope-open"></i>
                        </div>
                        @if (Auth::user()->isAdmin())<a href="{{ route('admin.invites') }}" class="small-box-footer">Explorar <i class="fas fa-arrow-circle-right"></i></a>@endif
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{{ $teamsc }}}</h3>
                            <p>@if ($teamsc == 1) Ponto @else Pontos @endif de Recolha</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-broadcast-tower"></i>
                        </div>
                        <a href="{{ route('pontos.index') }}" class="small-box-footer">Explorar <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{$msgc}}</h3>
                            <p>@if ($msgc == 1) Mensagem @else Mensagens @endif</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-database"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-orange">
                        <div class="inner">
                            <h3>{{$refc}}</h3>
                            <p>@if ($refc == 1) Referência @else Referências @endif</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-signal"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{$paramc}}</h3>
                            <p>@if ($paramc == 1) Parâmetro @else Parâmetros @endif</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
