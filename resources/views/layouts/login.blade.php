@include('partials.header')

<body class="hold-transition login-page">
    @include('partials.alerts')
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{{ url('/') }}}"><img src="/img/logo/logo.png" alt="MRIPTA" width="148"></a>
            </div>
            <div class="card-body">
                @yield('content')
            </div>
        </div>
    </div>

  @include('partials.scripts')

</body>
</html>
