<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <img src="{{asset('adminlte/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">{{env('APP_NAME')}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               <li class="nav-item">
                    <a href="{{url('/home')}}" class="nav-link @if (request()->is('home')) active @endif">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item @if (request()->is('master*'))
                    menu-open
                @endif">
                    <a href="#" class="nav-link @if (request()->is('master*'))
                        active
                    @endif">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Data Master
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('master.kategori.index') }}" class="nav-link @if (request()->is('master/kategori*')) active @endif">
                                <i class="far fa-folder nav-icon"></i>
                                <p>Kategori COA</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('master.chartofaccount.index') }}" class="nav-link @if (request()->is('master/chartofaccount*')) active @endif">
                                <i class="far fa-folder nav-icon"></i>
                                <p>COA</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            Transaksi
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../layout/top-nav.html" class="nav-link">
                                <i class="far fa-folder nav-icon"></i>
                                <p>Riwayat</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../layout/top-nav-sidebar.html" class="nav-link">
                                <i class="far fa-folder nav-icon"></i>
                                <p>Baru</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Laporan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../charts/chartjs.html" class="nav-link">
                                <i class="far fa-folder nav-icon"></i>
                                <p>Profit/Loss</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../charts/flot.html" class="nav-link">
                                <i class="far fa-folder nav-icon"></i>
                                <p>Buku Besar</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
