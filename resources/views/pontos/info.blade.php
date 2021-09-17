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
                        @if (!empty($dataset))
                        <table class="table table-striped" style="margin-bottom: 0" data-form="deleteForm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Referência</th>
                                    <th>Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataset as $key => $linha)
                                    <tr>
                                        <td>{{ $loop->index +1 }}</td>
                                        <td>{{ $key }}</td>
                                        <td>
                                            @if(Auth::user()->isOwner($team))
                                            <a href="{{ route('refs.edit', [$team->id, $key]) }}" class="btn btn-sm btn-info" tabindex="0" data-toggle="tooltip" title="Editar">
                                                <i class="far fa-edit"></i>
                                            </a>

                                            {{ Form::model($linha, ['method' => 'delete', 'route' => ['refs.destroy', $key], 'class'=>'btn btn-sm form-delete']) }}
                                            {{ Form::hidden('teamid', $team->id) }}
                                            {{ Form::button('<i class="far fa-trash-alt"></i>', ['class' => 'btn btn-sm btn-danger form-delete', 'data-toggle'=>'tooltip', 'title'=>'Eliminar']) }}
                                            {{ Form::close() }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <p>Não existem referências</p>
                        @endif
                    </div>
                    @if(Auth::user()->isOwner($team))
                    <div class="card-footer">
                        <a href="{{route('refs.create', $team->id)}}" class="btn btn-primary">Adicionar Referência</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Parâmetros</h3>
                    </div>
                    <div class="card-body">
                        @if (!empty($dataset))
                        <table class="table table-striped" style="margin-bottom: 0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Referência</th>
                                    @for ($i = 1; $i < $parammax+1; $i++)
                                    <th>Parâmetro {{ $i }}</th>
                                    @endfor
                                    <th>Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataset as $key => $linha)
                                    <tr>
                                        <td>{{ $loop->index +1 }}</td>
                                        <td>{{ $key }}</td>
                                        {{-- iterate refs --}}
                                        @foreach($linha as $ref)
                                        <td> {{$ref}} </td>
                                        @endforeach
                                        {{-- if the total number of params < number of line params --}}
                                        @if (count($linha) < $parammax)
                                        {{-- add empty columns --}}
                                        @for ($i = 0; $i < $parammax-count($linha); $i++)
                                        <td></td>
                                        @endfor
                                        @endif
                                        <td>
                                            @if(Auth::user()->isOwner($team))
                                            <a href="{{ route('params.edit', [$refs[$loop->index]->id]) }}" class="btn btn-sm btn-info" tabindex="0" data-toggle="tooltip" title="Gerir">
                                                <i class="fas fa-wrench"></i>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <p>Não existem parâmetros</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<!-- Modal -->
<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="confirmLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmLabel">Confirmação de Eliminação</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Tem a certeza que deseja eliminar a Referência selecionada e todos os Parâmetros associados?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-danger" id="delete-btn">Eliminar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
$(function () {
    $('[data-toggle="tooltip"]').tooltip({placement: 'bottom'})
})
$('table[data-form="deleteForm"]').on('click', '.form-delete', function(e){
    e.preventDefault();
    var $form=$(this);
    $('#confirm').modal({ backdrop: 'static', keyboard: false })
        .on('click', '#delete-btn', function(){
            $form.submit();
        });
});
@endsection