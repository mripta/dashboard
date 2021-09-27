<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
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
                    <a href="{{ route('home') }}" class="nav-link {{ (Request::is('/') ? 'active' : '') }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item has-treeview {{ (Request::is('pontos*') || Request::is('raw*') || Request::is('charts*') || Request::is('table*') || Request::is('ref*') || Request::is('alert*') || Request::is('params*') ? 'menu-open' : '') }}">
                    <a href="#" class="nav-link {{ (Request::is('pontos*') || Request::is('raw*') || Request::is('charts*') || Request::is('table*') || Request::is('ref*') || Request::is('alert*') || Request::is('params*') ? 'active' : '') }}">
                        <i class="nav-icon fas fa-broadcast-tower"></i>
                        <p>Pontos de Recolha
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right">{{count(Auth::user()->teams)}}</span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('pontos.create') }}" class="nav-link {{ (Request::is('pontos/create') ? 'active' : '') }}">
                                <i class="far fa-plus-square nav-icon"></i>
                                <p>Criar Ponto de Recolha</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pontos.index') }}" class="nav-link {{ (Request::is('pontos') ? 'active' : '') }}">
                                <i class="fas fa-list nav-icon"></i>
                                <p>Listar Pontos de Recolha</p>
                            </a>
                        </li>
                        @foreach(Auth::user()->teams as $team)
                        <li class="nav-item">
                            <a href="{{ route('pontos.info', ['pontoid' => $team->id]) }}" class="nav-link {{ (Request::is('pontos/'.$team->id) || Request::is('raw/'.$team->id) || Request::is('table/'.$team->id) || Request::is('charts/*/'.$team->id.'*') || Request::is('charts/live/*/'.$team->id.'*') || Request::is('ref/create/'.$team->id) || Request::is('ref/'.$team->id.'*') ? 'active' : '') }}">
                                <i class="fas fa-satellite-dish nav-icon @if($team->connected) text-success @endif"></i>
                                <p>{{$team->name}}</p>
                            </a>
                        </li>
                        @if(Request::is('pontos/'.$team->id) || Request::is('raw/'.$team->id) || Request::is('table/'.$team->id) || Request::is('charts/*/'.$team->id.'*') || Request::is('charts/live/*/'.$team->id.'*'))
                        <li class="nav-item has-treeview @if(Request::is('table/'.$team->id) || Request::is('raw/'.$team->id) || Request::is('charts/*/'.$team->id.'*') || Request::is('charts/live/*/'.$team->id.'*')) menu-open @endif">
                            <a href="#" class="nav-link @if(Request::is('table/'.$team->id) || Request::is('raw/'.$team->id) || Request::is('charts/*/'.$team->id.'*') || Request::is('charts/live/*/'.$team->id.'*')) active @endif">
                                <i class="fas fa-desktop nav-icon"></i> {{-- fa-file-alt --}}
                                <p> Visualização de Dados
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('live', ['line', $team->id]) }}" class="nav-link @if(Request::is('charts/live/*/'.$team->id.'*')) active @endif">
                                        <i class="fas fa-sync-alt nav-icon"></i>
                                        <p>Gráfico em Tempo Real</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('charts', ['line', $team->id]) }}" class="nav-link @if(Request::is('charts/line/'.$team->id.'*')) active @endif">
                                        <i class="fas fa-chart-line nav-icon"></i>
                                        <p>Gráfico de Linhas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('charts', ['bar', $team->id]) }}" class="nav-link @if(Request::is('charts/bar/'.$team->id.'*')) active @endif">
                                        <i class="fas fa-chart-bar nav-icon"></i>
                                        <p>Gráfico de Barras</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('charts', ['radar', $team->id]) }}" class="nav-link @if(Request::is('charts/radar/'.$team->id.'*')) active @endif">
                                        <i class="fas fa-chart-pie nav-icon"></i>
                                        <p>Gráfico de Radar</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('table', $team->id) }}" class="nav-link @if(Request::is('table/'.$team->id)) active @endif">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p>Tabela Formatada</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('raw', $team->id) }}" class="nav-link @if(Request::is('raw/'.$team->id)) active @endif">
                                        <i class="fas fa-file-code nav-icon"></i>
                                        <p>Tabela RAW</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </li>

                @if (Auth::user()->isAdmin())
                <li class="nav-header">Administração</li>

                <li class="nav-item">
                    <a href="{{ route('invite.index') }}" class="nav-link {{ (Request::is('admin/invite') ? 'active' : '') }}">
                        <i class="nav-icon fas fa-user-plus"></i>
                        <p>Gerir Convites</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ (Request::is('admin/user*') ? 'active' : '') }}">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>Gerir Utilizadores</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('teams.index') }}" class="nav-link {{ (Request::is('admin/team*') ? 'active' : '') }}">
                        <i class="nav-icon fas fa-satellite-dish"></i>
                        <p>Gerir Pontos de Recolha</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('alerts.index') }}" class="nav-link {{ (Request::is('admin/alert*') ? 'active' : '') }}">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>Gerir Alertas</p>
                    </a>
                </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>