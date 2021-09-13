@extends('layouts.admin')

@section('title', $title ?? '')

@section('content')
    <div class="content">
        <div class="container-fluid">
            @include('partials.alerts')
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user-plus"></i> Convidar Utilizador</h3>
                        </div>
                        <form method="post" action="{{ route('invite.create') }}">
                            <div class="card-body">
                                @csrf
                                <div class="form-group">
                                    <label for="email">Endereço de E-mail</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" aria-describedby="emailHelp" placeholder="Introduza o Endereço de E-mail">
                                    <small id="emailHelp" class="form-text text-muted">O utilizador receberá um convite no e-mail onde poderá completar o registo.</small>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">Enviar Convite</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-list-ol"></i> Lista de Pedidos de Convite</h3>
                        </div>
                        <div class="card-body">
                            @if (!empty($requests))
                            <table class="table table-striped" style="margin-bottom: 0" data-form="deleteForm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>E-mail</th>
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requests as $request)
                                        <tr>
                                            <td>{{ $loop->index +1 }}</td>
                                            <td>{{ $request->email }}</td>
                                            <td>
                                                {{ Form::model($request, ['route' => ['invite.notify', $request->id], 'class'=>'btn btn-sm']) }}
                                                {{ Form::button('<i class="fas fa-user-plus"></i>', ['type'=>'submit', 'class' => 'btn btn-sm btn-primary', 'data-toggle'=>'tooltip', 'title'=>'Convidar Utilizador']) }}
                                                {{ Form::close() }}

                                                {{ Form::model($request, ['method' => 'delete', 'route' => ['invite.destroy', $request->id], 'class'=>'btn btn-sm form-delete']) }}
                                                {{ Form::button('<i class="far fa-trash-alt"></i>', ['class' => 'btn btn-sm btn-danger form-delete', 'data-toggle'=>'tooltip', 'title'=>'Eliminar']) }}
                                                {{ Form::close() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                                <p>Não existem pedidos de registo</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-list-ol"></i> Lista de Convites Pendentes</h3>
                        </div>
                        <div class="card-body">
                            @if (!empty($pinvites))
                            <table class="table table-striped" style="margin-bottom: 0" data-form="deleteForm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>E-mail</th>
                                        <th>Data de Criação</th>
                                        <th>Link</th>
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pinvites as $pinvite)
                                        <tr>
                                            <td>{{ $loop->index +1 }}</td>
                                            <td>{{ $pinvite->email }}</td>
                                            <td>{{ $pinvite->created_at }}</td>
                                            <td>
                                                <kbd>{{ $pinvite->getLink() }}</kbd>
                                            </td>
                                            <td>
                                                {{ Form::model($pinvite, ['method' => 'delete', 'route' => ['invite.destroy', $pinvite->id], 'class'=>'btn btn-sm form-delete']) }}
                                                {{ Form::hidden('id', $pinvite->id) }}
                                                {{ Form::button('<i class="far fa-trash-alt"></i>', ['class' => 'btn btn-sm btn-danger form-delete', 'name' => 'deleteUser', 'id' => 'D'.$pinvite->id, 'data-toggle'=>'tooltip', 'title'=>'Eliminar']) }}
                                                {{ Form::close() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                                <p>Não existem Convites!</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-list-ol"></i> Lista de Convites Utilizados</h3>
                        </div>
                        <div class="card-body">
                            @if (!empty($uinvites))
                            <table class="table table-responsive table-striped" style="margin-bottom: 0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>E-mail</th>
                                        <th>Data de Criação</th>
                                        <th>Data de Aceitação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($uinvites as $uinvite)
                                        <tr>
                                            <td>{{ $loop->index +1 }}</td>
                                            <td>{{ $uinvite->email }}</td>
                                            <td>{{ date('d-m-Y H:m:s', strtotime($uinvite->created_at)) }}</td>
                                            <td>{{ date('d-m-Y H:m:s', strtotime($uinvite->registered_at)) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                                <p>Não existem Convites!</p>
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
        <p>Tem a certeza que deseja eliminar o convite selecionado?</p>
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