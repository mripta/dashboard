@extends('layouts.admin')

@section('title', $title." - ".$team->name)

@section('content')
<div class="content">
    <div class="container-fluid">
        @include('partials.alerts')
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{count($team->users)}}</h3>
                        <p>@if (count($team->users) == 1) Utilizador @else Utilizadores @endif</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{$datac}}</h3>
                        <p>@if ($datac == 1) Mensagem @else Mensagens @endif</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-envelope-open"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{count($team->refs)}}</h3>
                        <p>@if (count($team->refs) == 1) Referência @else Referências @endif</p>
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
        <!-- /. end top bar-->
        
        <h5>Visualização de Dados</h5>
        <div class="row">
            <div class="col-12">
                <div class="info-box card-primary card-outline">
                    <div class="col-sm text-center">
                        <a href="{{ route('charts', [$team->id, 'line']) }}" class="btn btn-app">
                            <i class="fas fa-chart-line"></i> Gráfico de Linhas
                        </a>
                    </div>
                    <div class="col-sm text-center">
                        <a href="{{ route('charts', [$team->id, 'bar']) }}" class="btn btn-app">
                            <i class="fas fa-chart-bar"></i> Gráfico de Barras
                        </a>
                    </div>
                    <div class="col-sm text-center">
                        <a href="{{ route('charts', [$team->id, 'radar']) }}" class="btn btn-app">
                            <i class="fas fa-chart-pie"></i> Gráfico de Radar
                        </a>
                    </div>
                    <div class="col-sm text-center">
                        <a href="{{ route('table', $team->id) }}" class="btn btn-app">
                            <i class="fas fa-list"></i> Tabela Formatada
                        </a>
                    </div>
                    <div class="col-sm text-center">
                        <a href="{{ route('raw', $team->id) }}" class="btn btn-app">
                            <i class="fas fa-file-code"></i> Tabela Raw
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Membros</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <ul class="users-list">
                            @foreach ($team->users as $user)
                            <li>
                                <img src="/img/profiles/{{$user->image}}" class="rounded" width="100" height="100" alt="Avatar {{$user->name}}">
                                <p class="users-list-name">{{$user->name}}</p>
                                @if($user->bio) <span class="users-list-date">{{ $user->bio }}</span> @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Autenticação e json para envio de dados</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" disabled value="{{ $team->username }}">
                        </div>

                        <div class="form-group">
                            <label for="json">JSON</label><br>
                            <code id="json">
                            @foreach($dataset as $key => $linha)
                            {"ref":"{{$key}}",
                                @foreach($linha as $ref)
                                    "{{$ref}}":"<var>xx</var>"@if(!$loop->last),@endif
                                @endforeach}<br>
                            @endforeach
                            </code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Referências</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                    @foreach($dataset as $key => $linha)
                        {{-- ref --}}
                        <div class="row">
                            <div class="input-group">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="ref">Referência</label>
                                    <input type="text" class="form-control" id="ref" disabled value="{{$key}}">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Gerir</label>
                                    <a href="{{ route('params.edit', [$refs[$loop->index]->id]) }}" class="btn btn-primary btn-block"><i class="fas fa-wrench"></i> Gerir</a>
                                </div>
                            </div>
                        </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Parâmetros</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                    @foreach($dataset as $key => $linha)
                        {{-- ref --}}
                        <div class="row">
                            <div class="input-group">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="ref">Referência</label>
                                    <input type="text" class="form-control" id="ref" disabled value="{{$key}}">
                                </div>
                            </div>
                            @foreach($linha as $ref)
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="param">Parâmetro</label>
                                    <input type="text" class="form-control" id="param" disabled value="{{$ref}}">
                                </div>
                            </div>
                            @endforeach
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Gerir</label>
                                    <a href="{{ route('params.edit', [$refs[$loop->index]->id]) }}" class="btn btn-primary btn-block"><i class="fas fa-wrench"></i> Gerir</a>
                                </div>
                            </div>
                        </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection