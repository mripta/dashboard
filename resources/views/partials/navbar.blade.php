<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <img src="/img/profiles/{{ Auth::user()->image }}" alt="Profile Image" class="img-circle" width="40" height="40" style="opacity: .8">
        </li>

        <!-- Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                {{ Auth::user()->name }} <i class="fas fa-angle-down"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('profile.definicoes') }}" class="dropdown-item {{ (Request::is('definicoes') ? 'active' : '') }}">
                    <i class="fas fa-id-card"></i> Modificar Perfil
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Terminar Sessão
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
