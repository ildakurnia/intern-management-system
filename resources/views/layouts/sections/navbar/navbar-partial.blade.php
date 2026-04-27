@php
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\Route;
@endphp

<!--  Brand demo (display only for navbar-full and hide on below xl) -->
@if (isset($navbarFull))
  <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-6">
    <a href="{{ url('/') }}" class="app-brand-link gap-2">
      <span class="app-brand-logo demo">@include('_partials.macros')</span>
      <span class="app-brand-text demo menu-text fw-semibold ms-1">{{ config('variables.templateName') }}</span>
    </a>

    <!-- Display menu close icon only for horizontal-menu with navbar-full -->
    @if (isset($menuHorizontal))
      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
        <i class="icon-base ri ri-close-line icon-sm"></i>
      </a>
    @endif
  </div>
@endif

<!-- ! Not required for layout-without-menu -->
@if (!isset($navbarHideToggle))
  <div
    class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 {{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
    <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
      <i class="icon-base ri ri-menu-line icon-md"></i>
    </a>
  </div>
@endif

<div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">

  @if (!isset($menuHorizontal))
    <!-- Search -->
    <div class="navbar-nav align-items-center">
      <div class="nav-item navbar-search-wrapper mb-0">
        <a class="nav-item nav-link search-toggler px-0" href="javascript:void(0);">
          <span class="d-inline-block text-body-secondary fw-normal" id="autocomplete"></span>
        </a>
      </div>
    </div>
    <!-- /Search -->
  @endif

  <ul class="navbar-nav flex-row align-items-center ms-md-auto">
    @if (isset($menuHorizontal))
      <!-- Search -->
      <li class="nav-item navbar-search-wrapper mb-0">
        <a class="nav-item nav-link search-toggler px-0" href="javascript:void(0);">
          <span class="d-inline-block text-body-secondary fw-normal" id="autocomplete"></span>
        </a>
      </li>
      <!-- /Search -->
    @endif

    <!-- Language -->
    <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
      <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
        <i class="icon-base ri ri-translate-2 icon-22px"></i>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li>
          <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{ url('lang/en') }}"
            data-language="en" data-text-direction="ltr">
            <span>English</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item {{ app()->getLocale() === 'fr' ? 'active' : '' }}" href="{{ url('lang/fr') }}"
            data-language="fr" data-text-direction="ltr">
            <span>French</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item {{ app()->getLocale() === 'ar' ? 'active' : '' }}" href="{{ url('lang/ar') }}"
            data-language="ar" data-text-direction="rtl">
            <span>Arabic</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item {{ app()->getLocale() === 'de' ? 'active' : '' }}" href="{{ url('lang/de') }}"
            data-language="de" data-text-direction="ltr">
            <span>German</span>
          </a>
        </li>
      </ul>
    </li>
    <!--/ Language -->

    @if ($configData['hasCustomizer'] == true)
      <!-- Style Switcher -->
      <li class="nav-item dropdown me-sm-2 me-xl-0">
        <a class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill" id="nav-theme"
          href="javascript:void(0);" data-bs-toggle="dropdown">
          <i class="icon-base ri ri-sun-line icon-22px theme-icon-active"></i>
          <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
          <li>
            <button type="button" class="dropdown-item align-items-center active" data-bs-theme-value="light"
              aria-pressed="false">
              <span><i class="icon-base ri ri-sun-line icon-22px me-3" data-icon="sun-line"></i>Light</span>
            </button>
          </li>
          <li>
            <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="dark"
              aria-pressed="true">
              <span><i class="icon-base ri ri-moon-clear-line icon-22px me-3"
                  data-icon="moon-clear-line"></i>Dark</span>
            </button>
          </li>
          <li>
            <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="system"
              aria-pressed="false">
              <span><i class="icon-base ri ri-computer-line icon-22px me-3" data-icon="computer-line"></i>System</span>
            </button>
          </li>
        </ul>
      </li>
      <!-- / Style Switcher-->
    @endif

    <!-- Quick links  -->
    <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-sm-2 me-xl-0">
      <a class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
        href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
        <i class="icon-base ri ri-star-smile-line icon-22px"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-end p-0">
        <div class="dropdown-menu-header border-bottom">
          <div class="dropdown-header d-flex align-items-center py-3">
            <h6 class="mb-0 me-auto">Shortcuts</h6>
            <a href="javascript:void(0)"
              class="btn btn-text-secondary rounded-pill btn-icon dropdown-shortcuts-add text-heading"
              data-bs-toggle="tooltip" data-bs-placement="top" title="Add shortcuts"> <i
                class="icon-base ri ri-add-line text-heading"></i> </a>
          </div>
        </div>
        <div class="dropdown-shortcuts-list scrollable-container">
          <div class="row row-bordered overflow-visible g-0">
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ri ri-calendar-line icon-26px text-heading"></i>
              </span>
              <a href="{{ url('app/calendar') }}" class="stretched-link">Calendar</a>
              <small>Appointments</small>
            </div>
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ri ri-file-text-line icon-26px text-heading"></i>
              </span>
              <a href="{{ url('app/invoice/list') }}" class="stretched-link">Invoice App</a>
              <small>Manage Accounts</small>
            </div>
          </div>
          <div class="row row-bordered overflow-visible g-0">
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ri ri-user-line icon-26px text-heading"></i>
              </span>
              <a href="{{ url('app/user/list') }}" class="stretched-link">User App</a>
              <small>Manage Users</small>
            </div>
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ri ri-computer-line icon-26px text-heading"></i>
              </span>
              <a href="{{ url('app/access-roles') }}" class="stretched-link">Role Management</a>
              <small>Permission</small>
            </div>
          </div>
          <div class="row row-bordered overflow-visible g-0">
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ri ri-pie-chart-2-line icon-26px text-heading"></i>
              </span>
              <a href="{{ url('/') }}" class="stretched-link">Dashboard</a>
              <small>User Dashboard</small>
            </div>
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ri ri-settings-4-line icon-26px text-heading"></i>
              </span>
              <a href="{{ url('pages/account-settings-account') }}" class="stretched-link">Setting</a>
              <small>Account Settings</small>
            </div>
          </div>
          <div class="row row-bordered overflow-visible g-0">
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ri ri-question-line icon-26px text-heading"></i>
              </span>
              <a href="{{ url('pages/faq') }}" class="stretched-link">FAQs</a>
              <small>FAQs & Articles</small>
            </div>
            <div class="dropdown-shortcuts-item col">
              <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                <i class="icon-base ri ri-tv-2-line icon-26px text-heading"></i>
              </span>
              <a href="{{ url('modal-examples') }}" class="stretched-link">Modals</a>
              <small>Useful Popups</small>
            </div>
          </div>
        </div>
      </div>
    </li>
    <!-- Quick links -->

    <!-- Notification -->
    @php
      $notifications = Auth::check()
        ? \App\Models\Notification::forUser(Auth::id())->limit(10)->get()
        : collect();
      $unreadCount = $notifications->whereNull('read_at')->count();
    @endphp
    <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-4 me-xl-1">
      <a class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
        href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
        <i class="icon-base ri ri-notification-2-line icon-22px"></i>
        @if ($unreadCount > 0)
          <span class="position-absolute top-0 start-50 translate-middle-y badge badge-dot bg-danger mt-2 border"></span>
        @endif
      </a>
      <ul class="dropdown-menu dropdown-menu-end py-0">
        <li class="dropdown-menu-header border-bottom py-50">
          <div class="dropdown-header d-flex align-items-center py-2">
            <h6 class="mb-0 me-auto">Notifikasi</h6>
            <div class="d-flex align-items-center h6 mb-0">
              @if ($unreadCount > 0)
                <span class="badge rounded-pill bg-label-primary fs-xsmall me-2">{{ $unreadCount }} Baru</span>
              @endif
              <a href="javascript:void(0)" id="mark-all-read-btn"
                class="dropdown-notifications-all p-2" data-bs-toggle="tooltip"
                data-bs-placement="top" title="Tandai semua dibaca">
                <i class="icon-base ri ri-mail-open-line text-heading"></i>
              </a>
            </div>
          </div>
        </li>
        <li class="dropdown-notifications-list scrollable-container">
          <ul class="list-group list-group-flush" id="notification-list">
            @forelse ($notifications as $notif)
              <li class="list-group-item list-group-item-action dropdown-notifications-item {{ $notif->isRead() ? 'marked-as-read' : '' }}"
                  id="notif-{{ $notif->id }}">
                <div class="d-flex">
                  <div class="flex-shrink-0 me-3">
                    <div class="avatar">
                      <span class="avatar-initial rounded-circle bg-label-{{ $notif->type }}">
                        <i class="icon-base ri {{ $notif->icon }} icon-18px"></i>
                      </span>
                    </div>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="small mb-1">{{ $notif->title }}</h6>
                    @if ($notif->body)
                      <small class="mb-1 d-block text-body">{{ $notif->body }}</small>
                    @endif
                    <small class="text-body-secondary">{{ $notif->created_at->diffForHumans() }}</small>
                  </div>
                  <div class="flex-shrink-0 dropdown-notifications-actions">
                    @if (!$notif->isRead())
                      <a href="javascript:void(0)" class="dropdown-notifications-read notif-read-btn"
                        data-notif-id="{{ $notif->id }}">
                        <span class="badge badge-dot"></span>
                      </a>
                    @endif
                    <a href="javascript:void(0)" class="dropdown-notifications-archive notif-delete-btn"
                      data-notif-id="{{ $notif->id }}">
                      <span class="icon-base ri ri-close-line"></span>
                    </a>
                  </div>
                </div>
              </li>
            @empty
              <li class="list-group-item text-center py-5" id="no-notif-msg">
                <i class="icon-base ri ri-notification-off-line icon-36px text-body-secondary mb-2 d-block mx-auto"></i>
                <small class="text-body-secondary">Tidak ada notifikasi</small>
              </li>
            @endforelse
          </ul>
        </li>
        <li class="border-top">
          <div class="d-grid p-4">
            <a class="btn btn-primary btn-sm d-flex justify-content-center" href="javascript:void(0);">
              <small class="align-middle">Lihat semua notifikasi</small>
            </a>
          </div>
        </li>
      </ul>
    </li>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
      const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      // Mark single as read
      document.querySelectorAll('.notif-read-btn').forEach(btn => {
        btn.addEventListener('click', function () {
          const id = this.dataset.notifId;
          fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
          }).then(r => r.json()).then(data => {
            if (data.success) {
              const item = document.getElementById(`notif-${id}`);
              if (item) {
                item.classList.add('marked-as-read');
                this.remove();
              }
              updateBadge();
            }
          });
        });
      });

      // Mark all as read
      const markAllBtn = document.getElementById('mark-all-read-btn');
      if (markAllBtn) {
        markAllBtn.addEventListener('click', function () {
          fetch('/notifications/read-all', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
          }).then(r => r.json()).then(data => {
            if (data.success) {
              document.querySelectorAll('.list-group-item.dropdown-notifications-item').forEach(el => {
                el.classList.add('marked-as-read');
              });
              document.querySelectorAll('.notif-read-btn').forEach(el => el.remove());
              updateBadge(true);
            }
          });
        });
      }

      // Delete / archive
      document.querySelectorAll('.notif-delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
          const id = this.dataset.notifId;
          fetch(`/notifications/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
          }).then(r => r.json()).then(data => {
            if (data.success) {
              const item = document.getElementById(`notif-${id}`);
              if (item) item.remove();
              if (document.querySelectorAll('.list-group-item.dropdown-notifications-item').length === 0) {
                document.getElementById('notification-list').innerHTML = `
                  <li class="list-group-item text-center py-5">
                    <i class="icon-base ri ri-notification-off-line icon-36px text-body-secondary mb-2 d-block mx-auto"></i>
                    <small class="text-body-secondary">Tidak ada notifikasi</small>
                  </li>`;
              }
              updateBadge();
            }
          });
        });
      });

      function updateBadge(forceZero = false) {
        const unread = forceZero ? 0 : document.querySelectorAll('.dropdown-notifications-item:not(.marked-as-read)').length;
        const dot = document.querySelector('.badge.badge-dot.bg-danger');
        const countBadge = document.querySelector('.badge.rounded-pill.bg-label-primary.fs-xsmall');
        if (dot) dot.style.display = unread > 0 ? '' : 'none';
        if (countBadge) {
          if (unread > 0) { countBadge.textContent = `${unread} Baru`; }
          else { countBadge.remove(); }
        }
      }
    });
    </script>
    <!--/ Notification -->
    <!-- User -->
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
      <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
          <img src="{{ Auth::check() && Auth::user()->profile_photo_path ? Storage::url(Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user() ? Auth::user()->name : 'User').'&background=666cff&color=fff' }}"
            alt="avatar" class="rounded-circle" />
        </div>
      </a>
      <ul class="dropdown-menu dropdown-menu-end mt-3 py-2">
        <li>
          <a class="dropdown-item"
            href="{{ Route::has('profile.show') ? route('profile.show') : url('pages/profile-user') }}">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0 me-2">
                <div class="avatar avatar-online">
                  <img src="{{ Auth::check() && Auth::user()->profile_photo_path ? Storage::url(Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user() ? Auth::user()->name : 'User').'&background=666cff&color=fff' }}"
                    alt="avatar" class="w-px-40 h-auto rounded-circle" />
                </div>
              </div>
              <div class="flex-grow-1">
                <h6 class="mb-0 small">
                  @if (Auth::check())
                    {{ Auth::user()->name }}
                  @else
                    John Doe
                  @endif
                </h6>
                <small class="text-body-secondary">Admin</small>
              </div>
            </div>
          </a>
        </li>
        <li>
          <div class="dropdown-divider"></div>
        </li>
        <li>
          <a class="dropdown-item"
            href="{{ Route::has('profile.show') ? route('profile.show') : url('pages/profile-user') }}">
            <i class="icon-base ri ri-user-3-line icon-22px me-2"></i> <span class="align-middle">My
              Profile</span> </a>
        </li>

        <li>
          <div class="dropdown-divider my-1"></div>
        </li>
        @if (Auth::check())
          <li>
            <div class="d-grid px-4 pt-2 pb-1">
              <a class="btn btn-danger d-flex" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <small class=" align-middle">Logout</small>
                <i class="icon-base ri ri-logout-box-r-line ms-2 icon-16px"></i>
              </a>
            </div>
          </li>
          <form method="POST" id="logout-form" action="{{ route('logout') }}">
            @csrf
          </form>
        @else
          <li>
            <div class="d-grid px-4 pt-2 pb-1">
              <a class="btn btn-danger d-flex"
                href="{{ Route::has('login') ? route('login') : url('auth/login-basic') }}">
                <small class="align-middle">Login</small>
                <i class="icon-base ri ri-logout-box-r-line ms-2 icon-16px"></i>
              </a>
            </div>
          </li>
        @endif
      </ul>
    </li>
    <!--/ User -->
  </ul>
</div>
