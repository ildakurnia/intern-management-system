@extends('layouts/contentNavbarLayout')

@section('title', 'Manajemen Role')

@section('page-style')
<style>
  .ims-theme-edit-btn {
    color: #fff;
    border: 0;
    box-shadow: none;
    transition: transform 0.18s ease, filter 0.18s ease;
  }

  html[data-bs-theme="light"] .ims-theme-edit-btn {
    background: linear-gradient(180deg, #36c76a 0%, #22a955 100%);
  }

  html[data-bs-theme="dark"] .ims-theme-edit-btn {
    background: linear-gradient(180deg, #5b7cff 0%, #3f67f2 100%);
  }

  .ims-theme-edit-btn:hover {
    color: #fff;
    transform: translateY(-1px);
    filter: brightness(1.03);
  }

  @media (max-width: 767.98px) {
    .roles-mobile-shell {
      display: grid;
      gap: 1rem;
    }

    .roles-mobile-card {
      border: 1px solid var(--bs-border-color);
      border-radius: 1rem;
      background: var(--bs-card-bg);
      box-shadow: 0 12px 28px rgba(47, 43, 61, 0.12);
      color: var(--bs-body-color);
      overflow: hidden;
    }

    .roles-mobile-card .card-body {
      padding: 1rem;
    }

    .roles-mobile-card .roles-mobile-head {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 0.75rem;
    }

    .roles-mobile-card .roles-mobile-title-wrap {
      display: flex;
      align-items: center;
      gap: 0.55rem;
      min-width: 0;
    }

    .roles-mobile-card .roles-mobile-title-icon {
      width: 2rem;
      height: 2rem;
      border-radius: 999px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      background: rgba(var(--bs-primary-rgb), 0.1);
      color: var(--bs-primary);
    }

    .roles-mobile-card .roles-mobile-title {
      margin: 0;
      color: var(--bs-heading-color);
      font-size: 1rem;
      font-weight: 700;
    }

    .roles-mobile-card .roles-mobile-subtitle {
      color: var(--bs-secondary-color);
      font-size: 0.875rem;
    }

    .roles-mobile-card .roles-mobile-meta {
      color: var(--bs-body-color);
      font-size: 0.78rem;
    }

    .roles-mobile-card .roles-mobile-stats {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-top: 1rem;
    }

    .roles-mobile-card .roles-mobile-stat {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      border-radius: 999px;
      white-space: nowrap;
    }

    .roles-mobile-card .roles-mobile-actions {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 0.5rem;
      margin-top: 1rem;
    }

    .roles-mobile-card .roles-mobile-actions .btn {
      width: 100%;
      border-radius: 0.75rem;
    }

    .roles-mobile-card .roles-mobile-delete {
      background: var(--bs-danger);
      border-color: var(--bs-danger);
      color: #fff;
    }

    .roles-mobile-card .roles-mobile-badge {
      border-radius: 999px;
      background: var(--bs-primary-bg-subtle);
      color: var(--bs-primary-text-emphasis);
      font-size: 0.75rem;
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      white-space: nowrap;
    }
  }
</style>
@endsection

@section('content')
  <div>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 mb-md-6 ims-mobile-toolbar">
      <div>
        <h4 class="mb-1">Daftar Role</h4>
        <p class="mb-0 text-body-secondary">Setiap role memberikan akses ke menu dan fitur yang sudah ditentukan.</p>
      </div>
      <a href="{{ route('roles.create') }}" class="btn btn-primary">
        <i class="icon-base ri ri-add-line me-1"></i> Tambah Role
      </a>
    </div>

    {{-- TAMPILAN DESKTOP (Tabel List) --}}
    <div class="card d-none d-md-block">
      <div class="table-responsive text-nowrap">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Role</th>
              <th>Guard Name</th>
              <th>Total Pengguna</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @forelse ($roles as $index => $role)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ ucfirst($role->name) }}</strong></td>
                <td><span class="badge bg-label-primary">{{ $role->guard_name }}</span></td>
                <td>
                  <div class="d-flex align-items-center">
                    <i class="icon-base ri ri-user-3-line text-body-secondary me-2"></i>
                    <span>{{ $role->users->count() }} Pengguna</span>
                  </div>
                </td>
                <td class="text-center">
                  <div class="d-flex align-items-center justify-content-center gap-2">
                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm ims-theme-edit-btn d-inline-flex align-items-center gap-1 ims-role-edit-btn" title="Edit Role">
                      <i class="icon-base ri ri-pencil-line"></i>
                      <span>Edit</span>
                    </a>
                    <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus role {{ $role->name }}?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 ims-role-delete-btn" title="Hapus Role">
                        <i class="icon-base ri ri-delete-bin-line"></i>
                        <span>Hapus</span>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-5">
                  <i class="icon-base ri ri-shield-user-line icon-32px text-muted mb-2"></i>
                  <p class="mb-0">Belum ada data role.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- TAMPILAN MOBILE (Card Compact) --}}
    <div class="roles-mobile-shell d-md-none">
      @forelse ($roles as $role)
        <div class="roles-mobile-card">
          <div class="card-body">
            <div class="roles-mobile-head">
              <div class="min-w-0">
                <div class="roles-mobile-title-wrap">
                  <span class="roles-mobile-title-icon">
                    <i class="ri ri-shield-user-line"></i>
                  </span>
                  <h6 class="roles-mobile-title text-truncate">{{ ucfirst($role->name) }}</h6>
                </div>
                <div class="roles-mobile-subtitle mt-1">{{ $role->users->count() }} pengguna terdaftar</div>
                <div class="roles-mobile-meta mt-2">
                  <i class="icon-base ri ri-user-3-line me-1" style="font-size: 14px;"></i>
                  Guard: {{ $role->guard_name }}
                </div>
              </div>
              <span class="badge roles-mobile-badge">
                <i class="icon-base ri ri-code-line"></i>
                <span>{{ $role->guard_name }}</span>
              </span>
            </div>

            <div class="roles-mobile-stats">
              <span class="badge bg-label-primary rounded-pill roles-mobile-stat">
                <i class="icon-base ri ri-user-3-line"></i>
                <span>{{ $role->users->count() }} Pengguna</span>
              </span>
            </div>

            <div class="roles-mobile-actions">
              <a href="{{ route('roles.edit', $role->id) }}" class="btn roles-mobile-edit ims-theme-edit-btn d-inline-flex align-items-center justify-content-center gap-1">
                <i class="icon-base ri ri-pencil-line"></i>
                <span>Edit</span>
              </a>

              <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Hapus role {{ $role->name }}?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn roles-mobile-delete d-inline-flex align-items-center justify-content-center gap-1">
                  <i class="icon-base ri ri-delete-bin-line"></i>
                  <span>Hapus</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      @empty
        <div class="roles-mobile-card text-center p-4">
          <div class="card-body">
            <i class="icon-base ri ri-shield-user-line icon-32px text-muted mb-2"></i>
            <p class="mb-0">Belum ada data role.</p>
          </div>
        </div>
      @endforelse
    </div>
  </div>
@endsection
