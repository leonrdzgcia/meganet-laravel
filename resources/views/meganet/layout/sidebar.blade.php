<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">Menu</li>

                <li>
                    <a href="{{ url('/') }}">
                        <i data-feather="home"></i>
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                <li>
                    @canany(['plan_view_internet','plan_view_voz','plan_view_custom','plan_view_paquetes'])
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="grid"></i>
                            <span data-key="t-apps">Planes</span>
                        </a>
                    @endcanany
                    <ul class="sub-menu" aria-expanded="false">
                        @can('plan_view_internet')
                            <li>
                                <a href="{{ url('internet') }}">
                                    <span data-key="t-internet"><small><i class="fa fa-fw fa-wifi"></i></small> Internet</span>
                                </a>
                            <li>
                        @endcan
                        @can('plan_view_voz')
                            <li>
                                <a href="{{ url('voz') }}">
                                        <span data-key="t-voz"><small><i
                                                    class="fa fa-fw fa-phone"></i></small> Voz</span>
                                </a>
                            </li>
                        @endcan
                        @can('plan_view_custom')
                            <li>
                                <a href="{{ url('custom') }}">
                                    <span data-key="t-custom"><small><i class="fa fa-fw fa-sitemap"></i></small> Personalizado</span>
                                </a>
                            </li>
                        @endcan
                        @can('plan_view_paquetes')
                            <li>
                                <a href="{{ url('paquetes') }}">
                                        <span data-key="t-paquetes"><small><i
                                                    class="fa fa-fw fa-object-group"></i></small>Paquetes</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <li>
                    @canany(['crm_view_crm','crm_add_crm'])
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="user-x"></i>
                            <span data-key="t-crm">CRM</span>
                        </a>
                    @endcan
                    <ul class="sub-menu" aria-expanded="false">
                        @can('crm_add_crm')
                            <li>
                                <a href="{{ url('/crm/crear') }}">
                                <span data-key="t-crm-crear"><small><i
                                            class="fa fa-fw fa-user"></i></small> Crear</span>
                                </a>
                            <li>
                        @endcan
                        @can('crm_view_crm')
                            <li>
                                <a href="{{ url('/crm/listar') }}">
                                <span data-key="t-crm-listar"><small><i
                                            class="fa fa-fw fa-list"></i></small> Listar</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <li>
                    @canany(['client_view_client','client_add_client'])
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="user-check"></i>
                            <span data-key="t-cliente">Cliente</span>
                        </a>
                    @endcan
                    <ul class="sub-menu" aria-expanded="false">
                        @can('client_add_client')
                            <li>
                                <a href="{{ url('/cliente/crear') }}">
                                <span data-key="t-cliente-crear"><small><i
                                            class="fa fa-fw fa-user"></i></small> Crear</span>
                                </a>
                            <li>
                        @endcan
                        @can('client_view_client')
                            <li>
                                <a href="{{ url('/cliente/listar') }}">
                                    <span data-key="t-cliente-listar"><small><i class="fa fa-fw fa-list"></i></small> Listar</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                <li>

                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="grid"></i>
                        <span data-key="t-cliente">Ticket</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                         <li>
                            <a href="{{ url('/tickets/') }}">
                                <span data-key="t-ticket-dashboard"><small><i
                                            class="mdi mdi-checkbox-blank-outline"></i></small>Dashboard</span>
                            </a>
                        <li>
                        <li>
                            <a href="{{ url('/tickets/abiertos') }}">
                                <span data-key="t-ticket-abierto"><small><i
                                            class="mdi mdi-checkbox-blank-outline"></i></small>Listar nuevo/abierto</span>
                            </a>
                        <li>
                        <li>
                            <a href="{{ url('/tickets/cerrados') }}">
                                <span data-key="t-ticket-cerrado"><small><i class="mdi mdi-checkbox-marked-outline"></i></small>Listar cerrados</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/tickets/reciclados') }}">
                                <span data-key="t-ticket-reciclaje"><small><i class="mdi mdi-trash-can-outline"></i></small>Listar reciclados</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>

                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="grid"></i>
                        <span data-key="t-cliente">Finanzas</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                         <li>
                            <a href="{{ url('/finanzas/transacciones') }}">
                                <span data-key="t-ticket-dashboard"><small><i
                                            class="mdi mdi-checkbox-blank-outline"></i></small>Transacciones</span>
                            </a>
                        <li>
                        <li>
                            <a href="{{ url('/finanzas/facturas') }}">
                                <span data-key="t-ticket-abierto"><small><i
                                            class="mdi mdi-checkbox-blank-outline"></i></small>Facturas</span>
                            </a>
                        <li>
                        <li>
                            <a href="{{ url('/finanzas/pagos') }}">
                                <span data-key="t-ticket-cerrado"><small><i class="mdi mdi-checkbox-marked-outline"></i></small>Pagos</span>
                            </a>
                        </li>
                        </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="map"></i>
                        <span data-key="t-cliente">Mapas</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ url('/mapas/') }}">
                                <span data-key="t-mapas-crear"><small><i
                                            class="fa fa-fw fa-map"></i></small> Listar</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="box"></i>
                        <span data-key="t-gestion-red">Gestión de red</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            @can('router_view_router')
                                <a href="javascript: void(0);" class="has-arrow">
                                    <i data-feather="box"></i>
                                    <span data-key="t-router">Enrutadores</span>
                                </a>
                            @endcan
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a href="{{ url('/red/router/crear') }}">
                                        <span data-key="t-router-crear"><small><i class="fa fa-fw fa-puzzle-piece"></i></small> Add</span>
                                    </a>
                                <li>
                                <li>
                                    <a href="{{ url('/red/router/listar') }}">
                                        <span data-key="t-router-listar"><small><i class="fa fa-fw fa-list"></i></small> Listar</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i data-feather="box"></i>
                                <span data-key="t-ipv4">Redes Ipv4</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a href="{{ url('/red/ipv4/crear') }}">
                                        <span data-key="t-ipv4-crear"><small><i
                                                    class="fa fa-fw fa-puzzle-piece"></i></small> Add</span>
                                    </a>
                                <li>

                                <li>
                                    <a href="{{ url('/red/ipv4/listar') }}">
                                        <span data-key="t-ipv4-listar"><small><i class="fa fa-fw fa-list"></i></small> Listar</span>
                                    </a>
                                </li>
                            </ul>

                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="command"></i>
                        <span data-key="t-administracion">Administracion</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ url('/administracion') }}">
                                <span data-key="t-administracion-ver"><small><i
                                            class="fa fa-fw fa-terminal"></i></small> Ver</span>
                            </a>
                        <li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="tool"></i>
                        <span data-key="t-configuracion">Configuracion</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ url('/configuracion') }}">
                                <span data-key="t-configuracion-ver"><small><i class="fa fa-fw fa-cogs"></i></small> Ver</span>
                            </a>
                        <li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
