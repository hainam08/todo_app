<div class="file-manager-sidebar">
    <div class="p-4 d-flex flex-column h-100">
        <div class="mb-3">
            <h5 class="fw-semibold">User Dashboard</h5>
        </div>
        <div class="px-4 mx-n4" data-simplebar style="height: calc(100vh - 100px);">
            <ul class="navbar-nav list-unstyled">
                <li class="nav-item">
                    <a class="nav-link menu-link" href="# }}">
                        <i class="ri-dashboard-line"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#">
                        <i class="ri-task-line"></i> <span>Create Task</span>
                    </a>
                </li>
                <li class="nav-item mt-auto">
                    <a class="nav-link menu-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ri-logout-box-line"></i> <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>