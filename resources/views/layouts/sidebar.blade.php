<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index" class="logo logo-dark">
            
            <span class="logo">
                            <h4 class="" style="margin-top: 20px; color: white">Hai Nam</h4>
                        </span>
        </a>
        <!-- Light Logo-->
        <a href="index" class="logo logo-light">
            
          <span class="logo">
                            <h4 class="" style="margin-top: 20px; color: white">Hai Nam</h4>
                        </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
    <div class="container-fluid">
        <div id="two-column-menu"></div>
        <ul class="navbar-nav" id="navbar-nav">
            <li class="menu-title"><span>Menu</span></li>

            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link menu-link" href="{{route('admin.dashboard')}}">
                    <i class="ri-dashboard-line"></i> <span>Dashboard</span>
                </a>
            </li>

            <!-- Task Management -->
            <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebarTasks" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTasks">
                    <i class="ri-task-line"></i> <span>Task Management</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarTasks">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{route('admin.tasks.index')}}" class="nav-link">All Tasks</a>
                        </li>
                        
                    </ul>
                </div>
            </li>

            <!-- User Management -->
            <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebarUsers" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarUsers">
                    <i class="ri-account-circle-line"></i> <span>User Management</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarUsers">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{route('admin.users.index')}}" class="nav-link">All Users</a>
                        </li>
                       
                    </ul>
                </div>
            </li>

            <!-- Statistics -->
            <li class="nav-item">
                <a class="nav-link menu-link" href="{{route('admin.statistics.index')}}">
                    <i class="ri-bar-chart-line"></i> <span>Statistics</span>
                </a>
            </li>

            <li class="menu-title"><i class="ri-more-fill"></i> <span>Account</span></li>

            <!-- Logout -->
            <li class="nav-item">
                <a class="nav-link menu-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i class="ri-logout-box-line"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- Sidebar -->
</div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
