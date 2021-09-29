@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="content">
    <div class="container-fluid">
        @include('partials.alerts')
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <form method="post" role="form" action="{{ route('refs.createpost', $teamid) }}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group{{ $errors->has('ref') ? ' has-error' : '' }}">
                                <label for="ref">Referência</label>
                                <input type="text" class="form-control" id="ref" name="ref"
                                       placeholder="Referência" value="{{ old('ref') ?: '' }}">
                                @if ($errors->has('ref'))
                                    <p class="text-danger">{{ $errors->first('ref') }}</p>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name">Nome da Referência</label>
                                <input type="name" class="form-control" id="name" name="name"
                                       placeholder="Nome da Referência" value="{{ old('name') ?: '' }}">
                                @if ($errors->has('name'))
                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection