@php
    $roleName = ucfirst(auth()->user()->getRoleNames()->first() ?? 'User');
@endphp

<header class="topbar" data-topbar>
    <div class="topbar-left">
        <button class="layout-menu-toggle mobile-toggle" type="button" aria-label="Toggle sidebar" data-sidebar-toggle>
            <span></span>
            <span></span>
            <span></span>
        </button>

        <form class="topbar-search" role="search">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M10.75 4a6.75 6.75 0 0 1 5.33 10.89l3.27 3.26a.85.85 0 0 1-1.2 1.2l-3.26-3.27A6.75 6.75 0 1 1 10.75 4Zm0 1.7a5.05 5.05 0 1 0 0 10.1 5.05 5.05 0 0 0 0-10.1Z"/>
            </svg>
            <input type="search" aria-label="Search" placeholder="Search [CTRL + K]">
        </form>
    </div>

    <div class="topbar-actions">
        <button class="topbar-icon-button" type="button" aria-label="Language">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12.65 17.5a9.47 9.47 0 0 0 2.55-4.65h2.65v-1.7h-2.4a13.9 13.9 0 0 0 .1-1.65V8.2h2.3V6.5H12.8V4h-1.7v2.5H5.95v1.7h7.9v1.3c0 .57-.04 1.12-.12 1.65H5.95v1.7h7.38a7.7 7.7 0 0 1-1.9 3.45 12.64 12.64 0 0 1-1.85-2.55H7.7a14.2 14.2 0 0 0 2.43 3.73 9.9 9.9 0 0 1-3.18 1.67l.65 1.58a11.45 11.45 0 0 0 3.8-2.05 11.5 11.5 0 0 0 3.7 2.05l.65-1.58a9.65 9.65 0 0 1-3.1-1.65Z"/>
            </svg>
        </button>
        <button class="topbar-icon-button" type="button" aria-label="Theme">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 4.25a.85.85 0 0 1 .85-.85h.01a.85.85 0 0 1 0 1.7h-.01a.85.85 0 0 1-.85-.85Zm0 14.65a.85.85 0 0 1 .85-.85h.01a.85.85 0 0 1 0 1.7h-.01a.85.85 0 0 1-.85-.85ZM4.25 12a.85.85 0 0 1-.85-.85v-.01a.85.85 0 0 1 1.7 0v.01a.85.85 0 0 1-.85.85Zm14.65 0a.85.85 0 0 1-.85-.85v-.01a.85.85 0 0 1 1.7 0v.01a.85.85 0 0 1-.85.85ZM6.51 6.51a.85.85 0 0 1 1.2-1.2l.01.01a.85.85 0 0 1-1.2 1.2l-.01-.01Zm9.77 9.77a.85.85 0 0 1 1.2-1.2l.01.01a.85.85 0 0 1-1.2 1.2l-.01-.01Zm1.21-9.77-.01.01a.85.85 0 0 1-1.2-1.2l.01-.01a.85.85 0 0 1 1.2 1.2ZM7.72 16.28l-.01.01a.85.85 0 0 1-1.2-1.2l.01-.01a.85.85 0 0 1 1.2 1.2ZM12 7.2a4.8 4.8 0 1 1 0 9.6 4.8 4.8 0 0 1 0-9.6Z"/>
            </svg>
        </button>
        <button class="topbar-icon-button" type="button" aria-label="Notifications">
            <span class="notification-dot"></span>
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 3.5a5.4 5.4 0 0 0-5.4 5.4v2.78c0 .75-.22 1.48-.64 2.1l-.98 1.47A1.45 1.45 0 0 0 6.18 17.5h11.64a1.45 1.45 0 0 0 1.2-2.25l-.98-1.47a3.8 3.8 0 0 1-.64-2.1V8.9A5.4 5.4 0 0 0 12 3.5Zm0 17a3.03 3.03 0 0 0 2.78-1.82H9.22A3.03 3.03 0 0 0 12 20.5Z"/>
            </svg>
        </button>

        <div class="user-menu">
            <button class="user-chip" type="button" data-user-menu-toggle aria-expanded="false">
                <img src="{{ asset('assets/img/avatars/1.png') }}" alt="User Avatar">
                <span class="user-status-dot"></span>
            </button>

            <div class="user-dropdown" data-user-menu>
                <div class="user-dropdown-header">
                    <strong>{{ auth()->user()->name }}</strong>
                    <span>{{ $roleName }}</span>
                </div>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                @role('intern')
                    <a href="{{ route('intern.profile.edit') }}">Profil Saya</a>
                @endrole
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>
    </div>
</header>
