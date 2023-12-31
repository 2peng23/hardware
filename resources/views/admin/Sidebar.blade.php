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

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider" />


    {{-- <!-- Heading -->
    <div class="sidebar-heading">Interface</div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Components</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Components:</h6>
                <a class="collapse-item" href="#">All Products</a>
                <a class="collapse-item" href="#">In Cart Products</a>
            </div>
        </div>
    </li> --}}

    {{-- <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block" /> --}}
    <li class="nav-item {{ request()->routeIs('categories') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('categories') }}">
            <i class="bi bi-ui-checks-grid"></i>
            <span>Categories</span></a>
    </li>
    <li class="nav-item {{ request()->routeIs('items') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('items') }}">
            <i class="fas fa-fw fa-shopping-bag"></i>
            <span>Items</span></a>
    </li>

    <li class="nav-item {{ request()->routeIs('inventory') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('inventory') }}">
            <i class="fa fa-fw fa-file"></i>
            <span>Inventory</span></a>
    </li>
    <li class="nav-item {{ request()->routeIs('request') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('request') }}" style="position: relative">
            <i class="fa fa-fw fa-refresh"></i>
            <span>Request</span>
            <span class="rounded-circle text-dark px-2"
                style="background-color: rgb(231, 216, 216);position:absolute; top:20px; right:20px">
                @if ($pending > 0)
                    {{ $pending }}
                @endif
            </span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin-users') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin-users') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>User</span>
        </a>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>


<!-- End of Sidebar -->
