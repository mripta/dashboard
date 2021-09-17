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
                            <table class="table table-striped projects">
                                <thead>
                                    <tr>
                                        <th style="width: 1%">
                                            #
                                        </th>
                                        <th style="width: 20%">
                                            Nome
                                        </th>
                                        <th style="width: 30%">
                                            Membros
                                        </th>
                                        <th style="width: 8%" class="text-center">
                                            Ligado
                                        </th>
                                        <th style="width: 20%">
                                            Opções
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($teams))
                                        @foreach ($teams as $row)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>
                                                    <a href="/pontos/{{$row->id}}">{{$row->name}}</a>
                                                    <br/>
                                                    <small>{{$row->description}}</small>
                                                    <br/>
                                                    <small>Criado a {{ date('d-m-Y', strtotime($row->created_at)) }}</small>
                                                </td>
                                                <td>
                                                    <ul class="list-inline">
                                                        @foreach ($row->users as $user)
                                                            <li class="list-inline-item">
                                                                <img alt="Perfil {{$user->name}}" class="table-avatar @if($user->pivot->owner) border border-danger @endif" data-toggle="tooltip" title="{{$user->name}}" src="/img/profiles/{{$user->image}}">
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td class="project-state">
                                                    @if($row->connected)
                                                        <span class="badge badge-success">Sim</span> 
                                                    @else
                                                    <span class="badge badge-danger">Não</span> 
                                                    @endif
                                                </td>
                                                <td class="project-actions">
                                                @if($row->pivot->owner)
                                                    <a href="{{ route('pontos.edit', $row->id) }}" id="{{$row->id}}" class="btn btn-sm btn-info"
                                                        tabindex="0" data-toggle="tooltip" title="Editar">
                                                         <i class="far fa-edit"></i>
                                                     </a>
                                                @endif
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
        <p>Tem a certeza que deseja eliminar o Ponto de Recolha selecionado?</p>
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
