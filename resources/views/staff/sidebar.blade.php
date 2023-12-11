<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fa fa-wrench"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Inventory Management System</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0" />

    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fa fa-fw fa-refresh"></i>
            <span>Request</span></a>
    </li>
    <li class="nav-item {{ request()->routeIs('point-of-sale') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('point-of-sale') }}">
            <i class="fa fa-fw fa-coins"></i>
            <span>POS</span></a>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
