@extends('layouts.admin')

@section('title', $title." - ".$teamname)

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <div class="card card-primary card-outline">
                  <div class="card-header">
                    <h3 class="card-title">Tabela Formatada</h3>
                    <div class="card-tools" style="display: flex">
                        <!-- form date picker-->
                        <form method="POST" id='data'>
                            @csrf
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" name="datepicker" class="form-control float-right" id="datepicker">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table class="table table-striped table-bordered" style="width:100%" id="table">
                      <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Ref</th>
                          <th>Param</th>
                          <th>Value</th>
                          <th>Data</th>
                        </tr>
                      </thead>
                      <tbody>
                        {{-- iterate $data mongodb messages --}}
                        @foreach ($data as $linha)
                          {{-- decode the payload as json --}}
                          @foreach(json_decode($linha->payload) as $key => $param)
                            {{-- remove the ref --}}
                            @if (!$loop->first)
                            <tr>
                              <td>{{$loop->iteration}}</td>
                              <td>{{ $linha->ref }}</td>
                              <td>{{ $key }}</td>
                              <td>{{ $param }}</td>
                              <td>{{ date('d/m/Y H:i:s', $linha->timestamp); }}</td>
                            </tr>
                            @endif
                          @endforeach
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </section>
@endsection

@section('scripts')
$(document).ready(function() {
  $('#table').DataTable({
    "dom": "<'row mb-2'<'col-12 text-right'B>>" + "<'row'<'col-sm-6'l><'col-sm-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons: [
        'csv', 'excel', 'pdf', 'print'
    ],
    language: {
        url: '../js/pt_PT.json'
    }
  });
  //Date range picker
  $('#datepicker').daterangepicker({
      locale: {
          format: 'DD/MM/YYYY'
      }
  })
  //$('#datepicker').val("");
});
@endsection