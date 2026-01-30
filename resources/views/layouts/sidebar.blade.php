<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ route('index') }}">
            <span class="align-middle">{{ get_option('site_title') }}</span>
        </a>
        <ul class="sidebar-nav">
            {{-- DASHBOARD --}}
            <li class="sidebar-item {{ request()->is('/') || request()->is('/dashboard') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('index') }}">
                    <i class="align-middle" data-feather="sliders"></i>
                    <span class="align-middle">Dashboard</span>
                </a>
            </li>
            @if (auth('admin')->check())
                <li class="sidebar-item {{ request()->is('pending-users') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('user.pending') }}">
                        <i class="align-middle" data-feather="users"></i>
                        <span class="align-middle">Pending Users ({{ pending_users_count() }})</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->is('active-users') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('user.active') }}">
                        <i class="align-middle" data-feather="users"></i>
                        <span class="align-middle">Users</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->is('transactions') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.transactions') }}">
                        <i class="align-middle" data-feather="dollar-sign"></i>
                        <span class="align-middle">All Transactions</span>
                    </a>
                </li>
            @endif
            @if (auth()->check())
                <li class="sidebar-item {{ request()->is('user/transactions') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('transactions') }}">
                        <i class="align-middle" data-feather="dollar-sign"></i>
                        <span class="align-middle">Transactions</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</nav>
