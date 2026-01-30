<nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle js-sidebar-toggle ps-3">
        <i class="hamburger align-self-center"></i>
    </a>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align">
            <li class="nav-item dropdown">
                <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                    <i class="align-middle" data-feather="settings"></i>
                </a>

                <a class="nav-link dropdown-toggle d-none d-sm-inline-block profile-navbar-dropdown me-3" href="#"
                    data-bs-toggle="dropdown">
                    <img src="{{ asset('assets/admin/img/no-user.jpg') }}"
                        class="avatar img-fluid rounded me-1" alt="Charles Hall" />
                    <span class="text-dark">
                        {{ auth('admin')->user()?->name ?? auth()->user()?->name ?? '-' }}
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end me-3">
                    <a class="dropdown-item" href="{{ route('profile') }}">
                        <i class="align-middle me-1" data-feather="user"></i>
                        Profile
                    </a>
                    <a class="dropdown-item" href="{{ route('change-password') }}">
                        <i class="align-middle me-1" data-feather="key"></i>
                        Change Password
                    </a>
                    <a class="dropdown-item" href="javascript:void(0);" data-href="{{ route('auth.logout') }}"
                        id="logoutBtn">
                        <i class="align-middle me-1" data-feather="lock"></i>
                        Log out
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>
