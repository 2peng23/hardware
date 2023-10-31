<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center my-3" href="{{ url('dashboard') }}">

        <div class="sidebar-brand-text">Inventory Management System <i class="fa fa-wrench"></i> </div>

    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0" />

    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fa fa-fw fa-refresh"></i>
            <span>Request</span></a>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
