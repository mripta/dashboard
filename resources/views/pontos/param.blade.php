@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="content">
    <div class="container-fluid">
        @include('partials.alerts')
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <form method="post" role="form" action="{{ route('params.patch', [$ref->id]) }}">
                        @csrf
                        @method('PATCH')
                        <div class="card-body">
                            <div class="form-group">
                                
                                <div class="input-group">
                                    <div class="col-sm-5">
                                        <label for="ref">Referência</label>
                                        <input type="text" class="form-control" id="ref" disabled value="{{$ref->ref}}">
                                    </div>
                                    <div class="col-sm-5">
                                        <label for="name">Nome da Referência</label>
                                        <input type="text" class="form-control" id="refname" disabled value="{{$ref->name}}"> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('param') ? ' has-error' : '' }}">
                                <label for="param">Parâmetros</label>
                                @if(count($params))
                                @foreach($params as $param)
                                <div class="input-group @if($loop->first) increment" @else "style="margin-top:5px" @endif>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="param" name="param[]" placeholder="Parâmetro" value="{{ $param->param }}"> 
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="paramname" name="paramname[]" placeholder="Nome do Parâmetro" value="{{ $param->name }}">
                                    </div>
                                    <div class="col-sm-2">
                                        @if($loop->first)
                                        <button class="btn btn-primary" id="add" type="button">Adicionar Linha</button>
                                        @else
                                        <button class="btn btn-danger" type="button">Remover Linha</button>
                                        @endif
                                    </div>
                                    @if($loop->first) <small id="emailHelp" class="form-text text-muted">Os parâmetros são utilizados para identificar leituras individuais</small> @endif
                                </div>
                                @endforeach
                                @else
                                <div class="input-group increment">
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="param" name="param[]" placeholder="Parâmetro"> 
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="paramname" name="paramname[]" placeholder="Nome do Parâmetro">
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-primary" id="add" type="button">Adicionar Linha</button>
                                    </div>
                                    <small id="emailHelp" class="form-text text-muted">Os parâmetros são utilizados para identificar leituras individuais</small>
                                </div>
                                @endif
                                @if ($errors->has('param'))
                                    <p class="text-danger">{{ $errors->first('param') }}</p>
                                @endif
                                @if ($errors->has('param.*'))
                                    <p class="text-danger">{{ $errors->first('param.*') }}</p>
                                @endif
                                @if ($errors->has('paramname'))
                                    <p class="text-danger">{{ $errors->first('paramname') }}</p>
                                @endif
                                @if ($errors->has('paramname.*'))
                                    <p class="text-danger">{{ $errors->first('paramname.*') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </div>
                    </form>
                    <div class="clone hide">
                        <div class="input-group" style="margin-top:5px">
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="param" name="param[]" placeholder="Parâmetro"> 
                            </div>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="paramname" name="paramname[]" placeholder="Nome do Parâmetro">
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-danger" type="button">Remover Linha</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
$(document).ready(function() {
    // clone the ref texbox
    $("#add").click(function(){ 
        var html = $(".clone").html();
        $(".increment").after(html);
    });
    // delete the ref textbox
    $("body").on("click",".btn-danger",function(){
        $('<input>').attr({
            type: 'hidden',
            name: 'deleteparam[]',
            value: $(this).parents(".input-group").find('#param').val()
        }).appendTo('form');
        $(this).parents(".input-group").remove();
    });
});
@endsection