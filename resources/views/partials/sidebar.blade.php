<aside class="sidebar">
    <div class="brand">
        <img src="{{ asset('assets/img/branding/logo.png') }}" alt="IMS Logo">
        <div>
            <p>IMS</p>
            <span>
                @role('admin') Admin Workspace @endrole
                @role('manager') Manager Workspace @endrole
                @role('intern') Intern Portal @endrole
            </span>
        </div>
    </div>

    <div class="sidebar-section-label">Menu Utama</div>
    <nav class="menu">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard*') ? 'active' : '' }}">
            <span class="menu-bullet"></span>
            <span>Dashboard</span>
        </a>
    </nav>

    @hasanyrole('admin|manager')
    <div class="sidebar-section-label">Management Ruang</div>
    <nav class="menu">
        @can('manage_interns')
        <a href="{{ route('managers.interns') }}" class="{{ request()->routeIs('managers.interns*') ? 'active' : '' }}">
            <span class="menu-bullet"></span>
            <span>Data Intern</span>
        </a>
        @endcan
        @can('review_daily_log')
        <a href="{{ route('managers.reports') }}" class="{{ request()->routeIs('managers.reports*') ? 'active' : '' }}">
            <span class="menu-bullet"></span>
            <span>Review Laporan</span>
        </a>
        @endcan
        @can('manage_attendance')
        <a href="{{ route('managers.attendance') }}" class="{{ request()->routeIs('managers.attendance*') ? 'active' : '' }}">
            <span class="menu-bullet"></span>
            <span>Monitoring Absensi</span>
        </a>
        @endcan
    </nav>
    
    @endhasanyrole

    @role('admin')
    <div class="sidebar-section-label">System (Admin Only)</div>
    <nav class="menu">
        <div class="menu-dropdown">
            <a href="javascript:void(0);" class="menu-toggle {{ request()->routeIs('roles*') || request()->routeIs('permissions*') ? 'active' : '' }}" onclick="this.nextElementSibling.classList.toggle('show')">
                <span class="menu-bullet"></span>
                <span>RBAC Management</span>
                <span style="margin-left: auto; font-size: 0.8em; opacity: 0.7;">▼</span>
            </a>
            <div class="submenu {{ request()->routeIs('roles*') || request()->routeIs('permissions*') ? 'show' : '' }}">
                <a href="{{ route('roles.index') }}" class="{{ request()->routeIs('roles*') ? 'active' : '' }}">
                    <span class="menu-bullet" style="background: transparent; border: 1px solid currentColor;"></span>
                    <span>Role Access</span>
                </a>
                <a href="{{ route('permissions.index') }}" class="{{ request()->routeIs('permissions*') ? 'active' : '' }}">
                    <span class="menu-bullet" style="background: transparent; border: 1px solid currentColor;"></span>
                    <span>Permission List</span>
                </a>
            </div>
        </div>
    </nav>
    <style>
        .submenu {
            display: none;
            padding-left: 1rem;
            flex-direction: column;
            gap: 0.25rem;
            margin-top: 0.5rem;
        }
        .submenu.show {
            display: flex;
        }
        .submenu a {
            padding: 0.5rem 1rem;
            font-size: 0.9em;
        }
    </style>
    @endrole

    @role('intern')
    <div class="sidebar-section-label">Aktivitas Magang</div>
    <nav class="menu">
        @can('submit_daily_log')
        <a href="{{ route('interns.daily_log') }}" class="{{ request()->routeIs('interns.daily_log*') ? 'active' : '' }}">
            <span class="menu-bullet"></span>
            <span>Isi Daily Log</span>
        </a>
        @endcan
        @can('submit_attendance')
        <a href="{{ route('interns.checkin') }}" class="{{ request()->routeIs('interns.checkin*') ? 'active' : '' }}">
            <span class="menu-bullet"></span>
            <span>Check In / Out</span>
        </a>
        @endcan
        @can('view_allowance')
        <a href="{{ route('interns.allowance') }}" class="{{ request()->routeIs('interns.allowance*') ? 'active' : '' }}">
            <span class="menu-bullet"></span>
            <span>Status Allowance</span>
        </a>
        @endcan
    </nav>
    @endrole

    <div class="sidebar-promo">
        <img src="{{ asset('assets/img/illustrations/rocket.png') }}" alt="IMS Promo">
        <h3>Stage 1 Active</h3>
        <p>Sidebar sudah dinamis. Menu yang muncul akan berbeda berdasarkan Role Spatie pengguna!</p>
    </div>
</aside>
