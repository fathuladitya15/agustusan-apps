 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
    <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Karang Taruna</span>
    </a>

    @if (Auth::check())
        <!-- Sidebar -->
        <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
            <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
            <a href="#" class="d-block">{{ getFullName() }}</a>
            </div>
        </div>


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item ">
                    <a href="{{ route('home') }}" class="nav-link {{ menuActive(['home']) }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                    </a>
                </li>

                <li class="nav-header">MANAJEMEN </li>
                @can('manage_household')
                <li class="nav-item">
                    <a href="{{ route('penduduk') }}" class="nav-link {{ menuActive('penduduk') }}  ">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                    Data Penduduk
                    </p>
                    </a>
                </li>

                @endcan
                @can('manage_users')
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ menuActive('users.index') }}  ">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                        Data Pengguna
                        </p>
                        </a>
                    </li>
                @endcan

                <li class="nav-header">BIAYA & PENGELUARAN</li>
                @can('manage_event')
                <li class="nav-item">
                    <a href="{{ route('event') }}" class="nav-link {{ menuActive(['event','detail.event','edit.detail.event.id']) }}  ">
                    <i class="nav-icon fas fa-file-invoice"></i>
                    <p>
                        Data Tagihan
                    </p>
                    </a>
                </li>

                @endcan


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->

    @endif
</aside>
