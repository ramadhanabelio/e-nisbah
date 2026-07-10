<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header justify-content-center" data-background-color="dark">

            {{-- {{ route('dashboard') }} --}}
            <a href="" class="logo">
                <img src="{{ asset('img/white.png') }}" alt="navbar brand" class="navbar-brand" height="45">
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>

        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-info">
                <li class="nav-item active">
                    <a href="">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Menu</h4>
                </li>

                <li class="nav-item">
                    <a href="">
                        <i class="fas fa-paper-plane"></i>
                        <p>Surat Keluar</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="">
                        <i class="fas fa-envelope"></i>
                        <p>Surat Masuk</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="">
                        <i class="fas fa-archive"></i>
                        <p>Arsip Surat</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Master Data</h4>
                </li>

                <li class="nav-item">
                    <a href="">
                        <i class="fas fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="">
                        <i class="fas fa-user-tag"></i>
                        <p>Roles</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
