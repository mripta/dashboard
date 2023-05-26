@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-primary" role="alert">
                      Results are limited to the last {{$hard_limit}} entries.
                    </div>
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{$title}}</h3>
                            <div class="card-tools" style="display: flex">
                                <!-- form date picker-->
                                <form method="POST">
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
                            <table id="raw" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Referência</th>
                                        <th>Payload</th>
                                        <th>Timestamp</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- iterate ref --}}
                                    @foreach ($data as $line) 
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ $line->ref }}</td>
                                        <td>{{ $line->payload }}</td>
                                        <td>{{ $line->timestamp }}</td>
                                        <td>{{ date('Y/m/d H:i:s', $line->timestamp) }}</td>
                                    </tr>
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
    $('#raw').DataTable({
        "dom": "<'row mb-2'<'col-12 text-right'B>>" + "<'row'<'col-sm-6'l><'col-sm-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            'excel', 'pdf', 'print'
        ],
        language: {
            url: '../js/pt_PT.json'
        },
        "order": [[4, "desc"]] // Sort by the fifth column (index 4) in descending order
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