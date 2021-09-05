<!-- Scripts -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/adminlte.min.js') }}"></script>
<script src="{{ asset('js/chart.min.js') }}"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/daterangepicker.js') }}"></script>

<script>
// select light or dar theme on OS settings
if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    $('body').addClass("dark-mode");
    $('.wrapper').find(".navbar").removeClass("navbar-light");
    $('.wrapper').find(".navbar").removeClass("navbar-white");
    $('.wrapper').find(".navbar").addClass("navbar-dark");
}
</script>

@yield('scripts')