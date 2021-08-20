<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{--{ route('frontend.index') }--}}" class="brand-link">
        <img src="/img/logo/mripta-128.png" alt="MRIPTA" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">MRIPTA</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{{ route('dashboard.home') }}}" class="nav-link {{{ (Request::is('/') ? 'active' : '') }}}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item has-treeview {{{ (Request::is('team*') ? 'menu-open' : '') }}}">
                    <a href="#" class="nav-link {{{ (Request::is('team*') ? 'active' : '') }}}">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>Equipas <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{--{ route('equipas.index') }--}}"
                               class="nav-link {{{ (Request::is('admin/equipas') ? 'active' : '') }}}">
                                <i class="fas fa-list nav-icon"></i>
                                <p>Listar Equipas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{--{ route('equipas.create') }--}}"
                               class="nav-link {{{ (Request::is('admin/equipas/create') ? 'active' : '') }}}">
                                <i class="fas fa-user-plus nav-icon"></i>
                                <p>Adicionar Equipa</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview {{{ (Request::is('admin/participantes*') ? 'menu-open' : '') }}}">
                    <a href="#" class="nav-link {{{ (Request::is('admin/participantes*') ? 'active' : '') }}}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Participantes <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{--{ route('participantes.index') }--}}"
                               class="nav-link {{{ (Request::is('admin/participantes') ? 'active' : '') }}}">
                                <i class="fas fa-list nav-icon"></i>
                                <p>Listar Participantes</p>
                            </a>
                        </li>
                        @if (Auth::user()->isAdmin())
                        <li class="nav-item">
                            <a href="{{--{ route('participantes.create') }--}}"
                               class="nav-link {{{ (Request::is('admin/participantes/create') ? 'active' : '') }}}">
                                <i class="fas fa-user-plus nav-icon"></i>
                                <p>Adicionar Participante</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @if (Auth::user()->isAdmin())
                <li class="nav-header">Administração</li>
                <li class="nav-item has-treeview {{{ (Request::is('users*') ? 'menu-open' : '') }}}">
                    <a href="#" class="nav-link {{{ (Request::is('users*') ? 'active' : '') }}}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>Utilizadores <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{{ route('user.index') }}}"
                               class="nav-link {{{ (Request::is('users') ? 'active' : '') }}}">
                                <i class="fas fa-list nav-icon"></i>
                                <p>Listar Utilizadores</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{{ route('user.create') }}}"
                               class="nav-link {{{ (Request::is('users/create') ? 'active' : '') }}}">
                                <i class="fas fa-user-plus nav-icon"></i>
                                <p>Adicionar Utilizador</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview {{{ (Request::is('admin/cargos*') ? 'menu-open' : '') }}}">
                    <a href="#" class="nav-link {{{ (Request::is('admin/cargos*') ? 'active' : '') }}}">
                        <i class="nav-icon fas fa-user-tag"></i>
                        <p>Equipas <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{--{ route('cargos.index') }--}}"
                               class="nav-link {{{ (Request::is('admin/cargos') ? 'active' : '') }}}">
                                <i class="fas fa-list nav-icon"></i>
                                <p>Listar Equipas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{--{ route('cargos.create') }--}}"
                               class="nav-link {{{ (Request::is('admin/cargos/create') ? 'active' : '') }}}">
                                <i class="fas fa-plus nav-icon"></i>
                                <p>Adicionar Equipa</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
