@if (Session::has('info'))
<div id="alertInfo" class="alert alert-info alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <i class="icon fa fa-info"></i> {!! Session::get('info') !!}
</div>
@endif

@if (Session::has('success'))
<div id="alertSuccess" class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <i class="icon fa fa-check"></i> {!! Session::get('success') !!}
</div>
@endif

@if (Session::has('warning'))
<div id="alertWarning" class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <i class="icon fa fa-warning"></i> {!! Session::get('warning') !!}
</div>
@endif

@if (Session::has('error'))
<div id="alertError" class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <i class="icon fa fa-ban"></i> {!! Session::get('error') !!}
</div>
@endif