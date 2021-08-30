@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="content">
        <div class="container-fluid">
            @include('partials.alerts')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <form method="post" role="form" action="{{ route('team.store') }}" data-parsley-validate>
                            <div class="card-body">
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name">Nome</label>
                                    <input type="text" value="{{ Request::old('name') ?: '' }}" class="form-control" id="name"
                                           name="name" placeholder="Preencher Nome">
                                    @if ($errors->has('name'))
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                    <label for="description">Descrição</label>
                                    <input type="text" value="{{ Request::old('description') ?: '' }}" class="form-control" id="description"
                                           name="description" placeholder="Preencher Descrição">
                                    @if ($errors->has('description'))
                                        <p class="text-danger">{{ $errors->first('description') }}</p>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                           placeholder="Preencher Password">
                                    @if ($errors->has('password'))
                                        <p class="text-danger">{{ $errors->first('password') }}</p>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <label for="password_confirmation">Confirmação da Password</label>
                                    <input type="password" value="{{ Request::old('password_confirmation') ?: '' }}" class="form-control" 
                                        id="password_confirmation" name="password_confirmation" placeholder="Preencher Confirmação da Password">
                                    @if ($errors->has('password_confirmation'))
                                        <p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
                                    @endif
                                </div>

                            </div>
                            <div class="card-footer">
                                @csrf
                                <button id="submit" type="submit" class="btn btn-success">Criar Utilizador</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
