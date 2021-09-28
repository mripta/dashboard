@include('partials.header')

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        @include('partials.navbar')

        @include('partials.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('title')</h1>
                        </div>
                    </div>
                </div>
            </div>

            @yield('content')
        </div>

        @yield('modal')

        @include('partials.footer')

    </div>

  @include('partials.scripts')

</body>
</html>
