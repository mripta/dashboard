<!-- Scripts -->
<script src="{{ asset('js/mripta.min.js') }}"></script>

<script type="text/javascript">
// select light or dar theme on OS settings
if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    $('body').addClass("dark-mode");
    $('.wrapper').find(".navbar").removeClass("navbar-light");
    $('.wrapper').find(".navbar").removeClass("navbar-white");
    $('.wrapper').find(".navbar").addClass("navbar-dark");
}
@yield('scripts')
</script>