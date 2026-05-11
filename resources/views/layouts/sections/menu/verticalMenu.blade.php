@php
  use Illuminate\Support\Facades\Auth;

  $configData = Helper::appClasses();
  $currentUser = Auth::user();
  $isRouteActive = static function ($slug): bool {
      $slugs = is_array($slug) ? $slug : [$slug];

      foreach ($slugs as $routeName) {
          if (! is_string($routeName) || $routeName === '') {
              continue;
          }

          if (request()->routeIs($routeName, $routeName . '.*')) {
              return true;
          }
      }

      return false;
  };
@endphp

@once
  <style>
    .layout-wrapper.layout-content-navbar {
      --bs-menu-width: 14.75rem;
      --bs-menu-collapsed-width: 4.5rem;
    }

    .ims-sidebar {
      display: flex;
      flex-direction: column;
      background: #f8fafc;
      border-inline-end: 1px solid rgba(148, 163, 184, 0.18);
      box-shadow: 0 24px 48px rgba(15, 23, 42, 0.06);
    }

    .ims-sidebar .app-brand {
      display: grid;
      grid-template-columns: minmax(0, 1fr) auto;
      gap: 0.65rem;
      height: auto;
      min-height: 0;
      padding: 0.82rem 0.72rem 0.68rem;
      margin-bottom: 0.15rem;
      align-items: start;
      border-bottom: 1px solid rgba(148, 163, 184, 0.12);
    }

    .ims-sidebar .app-brand-link {
      display: flex;
      align-items: flex-start;
      gap: 0.62rem !important;
      min-width: 0;
      width: 100%;
    }

    .ims-sidebar-mark {
      width: 2.4rem;
      height: 2.4rem;
      border-radius: 0.78rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #fff;
      border: 1px solid rgba(99, 102, 241, 0.12);
      box-shadow: 0 10px 22px rgba(99, 102, 241, 0.08);
      flex-shrink: 0;
    }

    .ims-sidebar-mark img {
      width: 1.3rem;
      height: 1.3rem;
      object-fit: contain;
    }

    .ims-sidebar-brand-copy {
      display: flex;
      flex-direction: column;
      min-width: 0;
    }

    .ims-sidebar-brand-title {
      margin: 0;
      color: #0f172a;
      font-size: 0.92rem;
      line-height: 1.1;
      font-weight: 700;
      letter-spacing: -0.02em;
    }

    .ims-sidebar-brand-meta {
      display: block;
      margin-top: 0.16rem;
      color: #64748b;
      font-size: 0.68rem;
      line-height: 1.3;
      white-space: normal;
    }

    .ims-sidebar .layout-menu-toggle {
      width: 2rem;
      height: 2rem;
      margin-left: 0;
      margin-top: 0.12rem;
      border-radius: 0.8rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #475569;
      background: #fff;
      border: 1px solid rgba(148, 163, 184, 0.22);
      transition: all 0.2s ease;
      flex-shrink: 0;
    }

    .ims-sidebar .layout-menu-toggle:hover {
      color: #4338ca;
      border-color: rgba(99, 102, 241, 0.28);
      background: rgba(99, 102, 241, 0.08);
    }

    .ims-sidebar .menu-inner-shadow {
      display: none;
    }

    .ims-sidebar-scroll {
      flex: 1 1 auto;
      overflow-y: auto;
      padding: 0.58rem 0.58rem 0.72rem;
    }

    .ims-sidebar .menu-inner {
      margin: 0;
      padding: 0 !important;
    }

    .ims-sidebar .menu-item {
      margin-bottom: 0.1rem;
    }

    .ims-sidebar .menu-link {
      min-height: 2.5rem;
      padding: 0.56rem 0.68rem;
      border-radius: 999px;
      color: #334155;
      font-size: 0.85rem;
      font-weight: 600;
      transition: all 0.2s ease;
      gap: 0.62rem;
    }

    .ims-sidebar .menu-link:hover {
      background: rgba(148, 163, 184, 0.12);
      color: #0f172a;
      transform: translateX(2px);
    }

    .ims-sidebar .menu-icon {
      width: 1rem;
      height: 1rem;
      margin-inline-end: 0;
      font-size: 0.95rem;
      color: inherit;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .ims-sidebar .menu-item.active > .menu-link,
    .ims-sidebar .menu-item.open > .menu-link {
      background: rgba(99, 102, 241, 0.12);
      color: #4338ca;
      box-shadow: inset 0 0 0 1px rgba(99, 102, 241, 0.12);
    }

    .ims-sidebar .menu-toggle::after {
      color: currentColor;
      inset-inline-end: 0.95rem;
    }

    .ims-sidebar .menu-header {
      display: flex;
      align-items: center;
      gap: 0.48rem;
      margin: 0.72rem 0 0.38rem !important;
      padding: 0 0.4rem;
    }

    .ims-sidebar .menu-header:first-child {
      margin-top: 0 !important;
    }

    .ims-sidebar .menu-header::after {
      content: '';
      flex: 1 1 auto;
      height: 1px;
      background: rgba(148, 163, 184, 0.16);
    }

    .ims-sidebar .menu-header-text {
      color: #94a3b8;
      font-size: 0.64rem;
      font-weight: 800;
      letter-spacing: 0.12em;
      text-transform: uppercase;
    }

    .ims-sidebar .menu-sub {
      margin: 0.16rem 0 0.38rem;
      padding: 0 0 0 0.62rem;
    }

    .ims-sidebar .menu-sub .menu-link {
      min-height: 2.2rem;
      padding: 0.48rem 0.62rem;
      font-size: 0.78rem;
      font-weight: 600;
      border-radius: 0.75rem;
    }

    .ims-sidebar .menu-sub .menu-icon {
      width: 0.84rem;
      height: 0.84rem;
      font-size: 0.78rem;
    }

    .ims-sidebar-footer {
      padding: 0.65rem 0.58rem 0.72rem;
      border-top: 1px solid rgba(148, 163, 184, 0.12);
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(10px);
    }

    .ims-sidebar-logout {
      width: 100%;
      border-radius: 999px;
      padding: 0.55rem 0.78rem;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.42rem;
    }

    .layout-menu-collapsed:not(.layout-menu-hover) .ims-sidebar .ims-sidebar-brand-copy,
    .layout-menu-collapsed:not(.layout-menu-hover) .ims-sidebar .menu-header,
    .layout-menu-collapsed:not(.layout-menu-hover) .ims-sidebar .menu-link > div,
    .layout-menu-collapsed:not(.layout-menu-hover) .ims-sidebar .ims-sidebar-logout span {
      display: none;
    }

    .layout-menu-collapsed:not(.layout-menu-hover) .ims-sidebar .app-brand {
      grid-template-columns: 1fr;
      justify-items: center;
      gap: 0.52rem;
      padding-inline: 0.58rem;
      padding-block: 0.72rem;
    }

    .layout-menu-collapsed:not(.layout-menu-hover) .ims-sidebar .app-brand-link {
      width: auto;
      justify-content: center;
      gap: 0 !important;
    }

    .layout-menu-collapsed:not(.layout-menu-hover) .ims-sidebar .layout-menu-toggle {
      margin-left: 0;
      margin-top: 0;
    }

    .layout-menu-collapsed:not(.layout-menu-hover) .ims-sidebar .menu-link {
      width: 2.7rem;
      min-height: 2.7rem;
      margin-inline: auto;
      padding: 0;
      justify-content: center;
      border-radius: 0.92rem;
    }

    .layout-menu-collapsed:not(.layout-menu-hover) .ims-sidebar .menu-link:hover {
      transform: none;
    }

    .layout-menu-collapsed:not(.layout-menu-hover) .ims-sidebar .menu-toggle::after,
    .layout-menu-collapsed:not(.layout-menu-hover) .ims-sidebar .menu-sub {
      display: none;
    }

    .layout-menu-collapsed:not(.layout-menu-hover) .ims-sidebar .ims-sidebar-footer {
      padding-inline: 0.58rem;
      padding-block: 0.68rem;
    }

    .layout-menu-collapsed:not(.layout-menu-hover) .ims-sidebar .ims-sidebar-logout {
      width: 3.2rem;
      min-height: 2.7rem;
      margin-inline: auto;
      padding: 0;
      gap: 0;
    }

    html[data-bs-theme="dark"] .ims-sidebar {
      background: #0f172a;
      border-inline-end-color: rgba(148, 163, 184, 0.14);
      box-shadow: 0 24px 48px rgba(2, 6, 23, 0.32);
    }

    html[data-bs-theme="dark"] .ims-sidebar .app-brand,
    html[data-bs-theme="dark"] .ims-sidebar-footer {
      border-color: rgba(148, 163, 184, 0.12);
    }

    html[data-bs-theme="dark"] .ims-sidebar-mark,
    html[data-bs-theme="dark"] .ims-sidebar .layout-menu-toggle {
      background: rgba(15, 23, 42, 0.7);
      border-color: rgba(148, 163, 184, 0.16);
      box-shadow: none;
    }

    html[data-bs-theme="dark"] .ims-sidebar-brand-title,
    html[data-bs-theme="dark"] .ims-sidebar .menu-link {
      color: #e2e8f0;
    }

    html[data-bs-theme="dark"] .ims-sidebar-brand-meta,
    html[data-bs-theme="dark"] .ims-sidebar .menu-header-text {
      color: #94a3b8;
    }

    html[data-bs-theme="dark"] .ims-sidebar .menu-link:hover {
      background: rgba(99, 102, 241, 0.14);
      color: #fff;
    }

    html[data-bs-theme="dark"] .ims-sidebar .menu-item.active > .menu-link,
    html[data-bs-theme="dark"] .ims-sidebar .menu-item.open > .menu-link {
      background: rgba(99, 102, 241, 0.18);
      color: #c7d2fe;
      box-shadow: inset 0 0 0 1px rgba(99, 102, 241, 0.12);
    }

  </style>
@endonce

<aside id="layout-menu" class="layout-menu menu-vertical menu ims-sidebar"
  @foreach ($configData['menuAttributes'] as $attribute => $value)
  {{ $attribute }}="{{ $value }}" @endforeach>

  @if (!isset($navbarFull))
    <div class="app-brand">
      <a href="{{ route('dashboard') }}" class="app-brand-link" aria-label="IMS Portal">
        <span class="ims-sidebar-mark">
          <img src="{{ asset('assets/img/branding/persero-logo.png') }}" alt="IMS">
        </span>
        <span class="ims-sidebar-brand-copy">
          <span class="ims-sidebar-brand-title">IMS Portal</span>
          <span class="ims-sidebar-brand-meta">Intern Management System</span>
        </span>
      </a>

      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large" aria-label="Toggle sidebar">
        <i class="ri ri-sidebar-fold-line"></i>
      </a>
    </div>
  @endif

  <div class="menu-inner-shadow"></div>

  <div class="ims-sidebar-scroll">
    <ul class="menu-inner">
      @foreach ($menuData[0]->menu as $menu)
        @if (isset($menu->menuHeader))
          <li class="menu-header small mt-5">
            <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
          </li>
        @else
          @php
            $activeClass = $isRouteActive($menu->slug ?? null)
                ? (isset($menu->submenu) ? 'active open' : 'active')
                : null;
          @endphp

          <li class="menu-item {{ $activeClass }}">
            <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
              class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
              title="{{ isset($menu->name) ? __($menu->name) : '' }}"
              @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
              @isset($menu->icon)
                <i class="{{ $menu->icon }}"></i>
              @endisset
              <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
              @isset($menu->badge)
                <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">{{ $menu->badge[1] }}</div>
              @endisset
            </a>

            @isset($menu->submenu)
              @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
            @endisset
          </li>
        @endif
      @endforeach
    </ul>
  </div>

  @if ($currentUser)
    <div class="ims-sidebar-footer">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-secondary ims-sidebar-logout">
          <i class="ri ri-logout-box-r-line"></i>
          <span>Logout</span>
        </button>
      </form>
    </div>
  @endif
</aside>
