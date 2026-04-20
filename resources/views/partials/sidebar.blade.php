<aside id="appSidebar" class="sidebar">
    <div class="brand">
        <a href="{{ route('dashboard') }}" class="brand-link" aria-label="IMS Dashboard">
            <img src="{{ asset('assets/img/branding/logo.png') }}" alt="IMS Logo">
        </a>
        <div class="brand-text">
            <p>IMS</p>
            <span>Portal Magang</span>
            <small>
                @role('superadmin') Superadmin @endrole
                @role('admin') Admin @endrole
                @role('mentor') Mentor @endrole
                @role('intern') Intern @endrole
            </small>
        </div>
        <button class="sidebar-collapse-toggle layout-menu-toggle" type="button" aria-label="Collapse sidebar" data-sidebar-collapse>
            <span>&lsaquo;</span>
        </button>
    </div>

    <div class="sidebar-section-label">Menu Utama</div>
    <nav class="menu">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard*') ? 'active' : '' }}">
            <span class="menu-icon">DB</span>
            <span class="menu-label">Dashboard</span>
        </a>
    </nav>

    @canany(['admin.interns.index', 'admin.logbooks.index'])
    <div class="sidebar-section-label">Manajemen Intern</div>
    <nav class="menu">
        @can('admin.interns.index')
        <a href="{{ route('admin.interns.index') }}" class="{{ request()->routeIs('admin.interns.index') || request()->routeIs('admin.interns.show') ? 'active' : '' }}">
            <span class="menu-icon">DI</span>
            <span class="menu-label">Data Intern</span>
        </a>
        @endcan
        @can('admin.interns.import')
            <a href="{{ route('admin.interns.import') }}" class="{{ request()->routeIs('admin.interns.import') ? 'active' : '' }}">
                <span class="menu-icon">IM</span>
                <span class="menu-label">Import Data</span>
            </a>
        @endcan
        @can('admin.intern-documents.index')
            <a href="{{ route('admin.intern-documents.index') }}" class="{{ request()->routeIs('admin.intern-documents.*') ? 'active' : '' }}">
                <span class="menu-icon">BI</span>
                <span class="menu-label">Berkas Intern</span>
            </a>
        @endcan
        @can('admin.logbooks.index')
            <a href="{{ route('admin.logbooks.index') }}" class="{{ request()->routeIs('admin.logbooks.*') ? 'active' : '' }}">
                <span class="menu-icon">LB</span>
                <span class="menu-label">Logbook Intern</span>
            </a>
        @endcan
    </nav>
    @endcanany

    @role('superadmin')
    <div class="sidebar-section-label">System (Superadmin Only)</div>
    <nav class="menu">
        <div class="menu-dropdown">
            <button type="button" class="menu-toggle {{ request()->routeIs('roles*') || request()->routeIs('permissions*') ? 'active' : '' }}" data-menu-dropdown-toggle aria-expanded="{{ request()->routeIs('roles*') || request()->routeIs('permissions*') ? 'true' : 'false' }}">
                <span class="menu-icon">RB</span>
                <span class="menu-label">RBAC Management</span>
                <span class="submenu-indicator">&#9662;</span>
            </button>
            <div class="submenu {{ request()->routeIs('roles*') || request()->routeIs('permissions*') ? 'show' : '' }}">
                @can('roles.index')
                <a href="{{ route('roles.index') }}" class="{{ request()->routeIs('roles*') ? 'active' : '' }}">
                    <span class="menu-icon menu-icon-outline">RL</span>
                    <span class="menu-label">Manajemen Role</span>
                </a>
                @endcan
                @can('permissions.index')
                <a href="{{ route('permissions.index') }}" class="{{ request()->routeIs('permissions*') ? 'active' : '' }}">
                    <span class="menu-icon menu-icon-outline">PL</span>
                    <span class="menu-label">Daftar Permission</span>
                </a>
                @endcan
            </div>
        </div>
    </nav>
    @endrole

    @role('mentor')
    @can('mentor.logbooks.index')
    <div class="sidebar-section-label">Monitoring Intern</div>
    <nav class="menu">
        <a href="{{ route('mentor.logbooks.index') }}" class="{{ request()->routeIs('mentor.logbooks.*') ? 'active' : '' }}">
            <span class="menu-icon">LB</span>
            <span class="menu-label">Logbook Intern</span>
        </a>
    </nav>
    @endcan
    @endrole

    @role('intern')
    <div class="sidebar-section-label">Onboarding Intern</div>
    <nav class="menu">
        @can('intern.profile.edit')
        <a href="{{ route('intern.profile.edit') }}" class="{{ request()->routeIs('intern.profile.*') ? 'active' : '' }}">
            <span class="menu-icon">PS</span>
            <span class="menu-label">Profil Saya</span>
        </a>
        @endcan
        @can('intern.documents.edit')
        <a href="{{ route('intern.documents.edit') }}" class="{{ request()->routeIs('intern.documents.*') ? 'active' : '' }}">
            <span class="menu-icon">BS</span>
            <span class="menu-label">Berkas Saya</span>
        </a>
        @endcan
        @can('intern.logbooks.index')
        <a href="{{ route('intern.logbooks.index') }}" class="{{ request()->routeIs('intern.logbooks.*') ? 'active' : '' }}">
            <span class="menu-icon">LB</span>
            <span class="menu-label">Logbook</span>
        </a>
        @endcan
    </nav>
    @endrole
</aside>
