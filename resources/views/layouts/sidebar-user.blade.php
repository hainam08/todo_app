<div class="file-manager-sidebar">
    <div class="p-4 d-flex flex-column h-100">
        <div class="mb-3">
            <h5 class="fw-semibold">User Dashboard</h5>
        </div>
        <div class="px-4 mx-n4" data-simplebar style="height: calc(100vh - 100px);">
            <ul class="navbar-nav list-unstyled">
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{route('user.dashboard')}}">
                        <i class="ri-dashboard-line"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{route('user.index')}}">
                        <i class="ri-task-line"></i> <span>All Task</span>
                    </a>
                </li>
               <li class="nav-item mt-auto">
                    <a class="nav-link menu-link text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="ri-logout-box-line"></i> <span>Logout</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>