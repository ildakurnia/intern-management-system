@php
  use Illuminate\Support\Facades\Auth;
@endphp

@once
  <style>
    #layout-navbar,
    #layout-navbar .container-xxl,
    #layout-navbar .container-fluid {
      background: transparent !important;
      box-shadow: none !important;
      border: 0 !important;
    }

    .layout-navbar-fixed .window-scrolled #layout-navbar,
    .window-scrolled #layout-navbar {
      box-shadow: none !important;
      backdrop-filter: none !important;
    }

    .ims-topbar-shell {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.85rem;
      width: 100%;
      padding: 0.58rem 0.9rem;
      border-radius: 1rem;
      background: rgba(255, 255, 255, 0.56);
      border: 1px solid rgba(148, 163, 184, 0.1);
      box-shadow: 0 12px 28px rgba(15, 23, 42, 0.045);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      transition: background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .layout-navbar-fixed .window-scrolled .ims-topbar-shell,
    .window-scrolled .ims-topbar-shell {
      background: rgba(255, 255, 255, 0.42);
      border-color: rgba(148, 163, 184, 0.08);
      box-shadow: 0 10px 24px rgba(15, 23, 42, 0.035);
    }

    .ims-topbar-actions {
      gap: 0.22rem;
    }

    .ims-topbar-icon {
      width: 2.15rem;
      height: 2.15rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 999px;
      color: #475569;
      transition: all 0.2s ease;
    }

    .ims-topbar-icon:hover {
      background: rgba(99, 102, 241, 0.08);
      color: #4338ca;
    }

    .ims-topbar-user .avatar {
      --bs-avatar-size: 2.15rem;
    }

    .ims-topbar-menu-toggle {
      width: 2.15rem;
      height: 2.15rem;
      border-radius: 999px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #334155;
      background: rgba(148, 163, 184, 0.1);
    }

    html[data-bs-theme="dark"] .ims-topbar-shell {
      background: rgba(15, 23, 42, 0.56);
      border-color: rgba(148, 163, 184, 0.14);
      box-shadow: 0 18px 40px rgba(2, 6, 23, 0.22);
    }

    html[data-bs-theme="dark"] .layout-navbar-fixed .window-scrolled .ims-topbar-shell,
    html[data-bs-theme="dark"] .window-scrolled .ims-topbar-shell {
      background: rgba(15, 23, 42, 0.42);
      border-color: rgba(148, 163, 184, 0.12);
    }

    html[data-bs-theme="dark"] .ims-topbar-icon,
    html[data-bs-theme="dark"] .ims-topbar-menu-toggle {
      color: #cbd5e1;
    }

    @media (max-width: 991.98px) {
      .ims-topbar-shell {
        padding: 0.58rem 0.8rem;
      }

    }
  </style>
@endonce

<div class="navbar-nav-right d-flex align-items-center justify-content-end w-100" id="navbar-collapse">
  <div class="ims-topbar-shell">
    <div class="d-flex align-items-center gap-3 min-w-0">
      @if (!isset($navbarHideToggle))
        <div class="layout-menu-toggle navbar-nav align-items-xl-center d-xl-none">
          <a class="nav-item nav-link px-0 ims-topbar-menu-toggle" href="javascript:void(0)">
            <i class="icon-base ri ri-menu-line icon-md"></i>
          </a>
        </div>
      @endif
    </div>

    <ul class="navbar-nav flex-row align-items-center ims-topbar-actions ms-auto">
      @if ($configData['hasCustomizer'] == true)
        <li class="nav-item dropdown me-1">
          <a class="nav-link dropdown-toggle hide-arrow ims-topbar-icon" id="nav-theme"
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
      @endif

      @php
        $notifications = Auth::check() && \Illuminate\Support\Facades\Schema::hasTable('notifications')
          ? \App\Models\Notification::forUser(Auth::id())->limit(10)->get()
          : collect();
        $unreadCount = $notifications->whereNull('read_at')->count();
      @endphp
      <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-2">
        <a class="nav-link dropdown-toggle hide-arrow ims-topbar-icon"
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

      <li class="nav-item navbar-dropdown dropdown-user dropdown ims-topbar-user">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          @php
            $userName = Auth::check() ? Auth::user()->name : 'User';
            $avatarInitials = collect(explode(' ', trim((string) $userName)))
              ->filter()
              ->take(2)
              ->map(fn ($part) => strtoupper(mb_substr($part, 0, 1)))
              ->implode('');
          @endphp
          <div class="avatar avatar-online">
            <span class="avatar-initial rounded-circle bg-primary text-white fw-semibold">
              {{ $avatarInitials ?: 'U' }}
            </span>
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end mt-3 py-2">
          <li>
            <a class="dropdown-item"
              href="{{ Auth::check() && Auth::user()->hasRole('intern') ? route('intern.profile.edit') : route('profile.edit') }}">
              <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-2">
                  <div class="avatar avatar-online">
                    <span class="avatar-initial rounded-circle bg-primary text-white fw-semibold">
                      {{ $avatarInitials ?: 'U' }}
                    </span>
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-0 small">
                    @if (Auth::check())
                      {{ Auth::user()->name }}
                    @else
                      User
                    @endif
                  </h6>
                  <small class="text-body-secondary text-capitalize">{{ Auth::check() ? Auth::user()->getRoleNames()->first() : 'Guest' }}</small>
                </div>
              </div>
            </a>
          </li>
          <li><div class="dropdown-divider"></div></li>
          <li>
            <a class="dropdown-item"
              href="{{ Auth::check() && Auth::user()->hasRole('intern') ? route('intern.profile.edit') : route('profile.edit') }}">
              <i class="icon-base ri ri-user-3-line icon-22px me-2"></i>
              <span class="align-middle">Profil Saya</span>
            </a>
          </li>
          <li><div class="dropdown-divider my-1"></div></li>
          @if (Auth::check())
            <li>
              <div class="d-grid px-4 pt-2 pb-1">
                <a class="btn btn-danger d-flex" href="{{ route('logout') }}"
                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <small class="align-middle">Logout</small>
                  <i class="icon-base ri ri-logout-box-r-line ms-2 icon-16px"></i>
                </a>
              </div>
            </li>
            <form method="POST" id="logout-form" action="{{ route('logout') }}">
              @csrf
            </form>
          @endif
        </ul>
      </li>
    </ul>
  </div>
</div>
