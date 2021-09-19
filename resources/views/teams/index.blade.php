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
                                <table class="table table-striped" id="dataTable" width="100%" cellspacing="0" data-toggle="dataTable" data-form="deleteForm">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>Descrição</th>
                                        <th>Username</th>
                                        <th>Opções</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($teams))
                                        @foreach ($teams as $row)
                                            <tr>
                                                <td>{{$row->id}}</td>
                                                <td>{{$row->name}}</td>
                                                <td>{{$row->description}}</td>
                                                <td>{{$row->username}}</td>
                                                <td>
                                                    <a href="{{ route('teams.edit', $row->id) }}" id="{{$row->id}}" class="btn btn-sm btn-info"
                                                       tabindex="0" data-toggle="tooltip" title="Editar">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                    {{ Form::model($row, ['method' => 'delete', 'route' => ['teams.destroy', $row->id], 'class'=>'btn btn-sm form-delete']) }}
                                                    {{ Form::hidden('id', $row->id) }}
                                                    {{ Form::button('<i class="far fa-trash-alt"></i>', ['class' => 'btn btn-sm btn-danger form-delete', 'name' => 'deleteTeam', 'id' => 'D'.$row->id.'', 'tabindex'=>'0', 'data-toggle'=>'tooltip', 'title'=>'Eliminar']) }}
                                                    {{ Form::close() }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
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
        <p>Tem a certeza que deseja eliminar a equipa selecionada?</p>
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
