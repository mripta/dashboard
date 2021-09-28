@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="content">
        <div class="container-fluid">
            @include('partials.alerts')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <div class="table-responsive">
                                @if(count($alerts))
                                <table class="table table-striped" id="dataTable" width="100%" cellspacing="0" data-toggle="dataTable" data-form="deleteForm">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>Equipa</th>
                                        <th>Referência</th>
                                        <th>Parâmetro</th>
                                        <th>Min</th>
                                        <th>Max</th>
                                        <th>Ativo</th>
                                        <th>Opções</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($alerts as $alert)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$alert->name}}</td>
                                                <td>{{$alert->team->name}}</td>
                                                <td>{{$alert->ref->ref}}</td>
                                                <td>{{$alert->param->param}}</td>
                                                <td>{{$alert->min}}</td>
                                                <td>{{$alert->max}}</td>
                                                <td>
                                                @if($alert->enabled)
                                                    <span class="badge badge-success">Sim</span> 
                                                @else
                                                    <span class="badge badge-danger">Não</span> 
                                                @endif
                                                </td>
                                                <td>
                                                @if($alert->enabled)
                                                <a href="{{ route('alerts.toggle', $alert->id) }}" id="{{$alert->id}}" style="margin-right:9px" class="btn btn-sm btn-danger"
                                                    tabindex="0" data-toggle="tooltip" title="Desativar Alerta">
                                                     <i class="fas fa-power-off"></i>
                                                </a>
                                                @else
                                                <a href="{{ route('alerts.toggle', $alert->id) }}" id="{{$alert->id}}" style="margin-right:9px" class="btn btn-sm btn-success"
                                                    tabindex="0" data-toggle="tooltip" title="Ativar Alerta">
                                                     <i class="fas fa-power-off"></i>
                                                </a>
                                                @endif
                                                    <a href="{{ route('alerts.edit', $alert->id) }}" id="{{$alert->id}}" class="btn btn-sm btn-info"
                                                       tabindex="0" data-toggle="tooltip" title="Editar">
                                                        <i class="far fa-edit"></i>
                                                    </a>

                                                    {{ Form::model($alert, ['method' => 'delete', 'route' => ['alerts.destroy', $alert->id], 'class'=>'btn btn-sm form-delete']) }}
                                                    {{ Form::button('<i class="far fa-trash-alt"></i>', ['class' => 'btn btn-sm btn-danger form-delete', 'name' => 'deleteUser', 'id' => 'D'.$alert->id, 'data-toggle'=>'tooltip', 'title'=>'Eliminar']) }}
                                                    {{ Form::close() }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else 
                                    <p>Não existem alertas</p>
                                @endif
                            </div>
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
        <p>Tem a certeza que deseja eliminar o alerta selecionado?</p>
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
