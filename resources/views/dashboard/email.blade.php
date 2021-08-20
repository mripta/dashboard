@extends('layouts.admin')

@php ($title = "Enviar Email")

@section('title', $title)

@section('content')
    <section class="content">
        <div class="container-fluid">
            @include('partials.alerts')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <div class="form-group">
                                <input class="form-control" placeholder="Para:">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Assunto:">
                            </div>
                            <div class="form-group">
                                <textarea id="compose-textarea" class="form-control" style="height: 300px">
                                </textarea>
                            </div>
                            <div class="card-footer">
                                <div class="float-right">
                                    <button type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> Enviar</button>
                                </div>
                                <button type="reset" class="btn btn-default"><i class="fas fa-times"></i> Limpar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
    $(function () {
    //Add text editor
    $('#compose-textarea').summernote()
    })
    </script>
@endsection
