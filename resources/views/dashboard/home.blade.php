@extends('layouts.admin')

@section('title', $title)

@section('content')
    <section class="content">
        <div class="container-fluid">
            @include('partials.alerts')
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{{ $users }}}</h3>
                            <p>@if ($users == 1) Utilizador @else Utilizadores @endif</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user"></i>
                        </div>
                        @if (Auth::user()->isAdmin())<a href="#" class="small-box-footer">Explorar <i class="fas fa-arrow-circle-right"></i></a>@endif
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{{ $teams }}}</h3>
                            <p>@if ($teams == 1) Equipa @else Equipas @endif</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        a href="#" class="small-box-footer">Explorar <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                {{-- 
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-school"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Politécnicos</span>
                            <span class="info-box-number"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-friends"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Equipas</span>
                            <span class="info-box-number">{{{ $teams }}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Participantes</span>
                            <span class="info-box-number"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-shield"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Utilizadores</span>
                            <span class="info-box-number">{{{ $users }}}</span>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>
@endsection
