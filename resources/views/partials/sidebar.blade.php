<aside class="sidebar">
    <div class="brand">
        <img src="{{ asset('assets/img/branding/logo.png') }}" alt="IMS Logo">
        <div>
            <p>IMS</p>
            <span>Admin Workspace</span>
        </div>
    </div>

    <div class="sidebar-section-label">Menu</div>
    <nav class="menu">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard*') ? 'active' : '' }}">
            <span class="menu-bullet"></span>
            <span>Dashboard</span>
        </a>
    </nav>

    <div class="sidebar-promo">
        <img src="{{ asset('assets/img/illustrations/rocket.png') }}" alt="IMS Promo">
        <h3>Stage 1 Active</h3>
        <p>Authentication, role access, dan dashboard dasar sudah siap direview.</p>
    </div>
</aside>
