@extends('layouts/contentNavbarLayout')

@section('title', 'Manajemen Pengguna')

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
    .users-mobile-shell {
      display: grid;
      gap: 1rem;
    }

    .users-mobile-card {
      border: 1px solid var(--bs-border-color);
      border-radius: 1rem;
      background: var(--bs-card-bg);
      overflow: hidden;
    }

    .users-mobile-card .card-header {
      background: transparent;
    }

    .users-mobile-item {
      padding: 1rem;
    }

    .users-mobile-item + .users-mobile-item {
      border-top: 1px solid var(--bs-border-color);
    }

    .users-mobile-name {
      margin: 0;
      color: var(--bs-heading-color);
      font-size: 1rem;
      font-weight: 700;
      line-height: 1.25;
    }

    .users-mobile-email,
    .users-mobile-meta {
      color: var(--bs-secondary-color);
      font-size: 0.875rem;
    }

    .users-mobile-meta-list {
      display: grid;
      gap: 0.45rem;
      margin-top: 0.9rem;
    }

    .users-mobile-meta-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: var(--bs-body-color);
      font-size: 0.875rem;
      line-height: 1.35;
    }

    .users-mobile-actions {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 0.5rem;
      margin-top: 1rem;
    }

    .users-mobile-actions .btn {
      width: 100%;
      border-radius: 0.75rem;
    }

    .users-mobile-delete {
      background: var(--bs-danger);
      border-color: var(--bs-danger);
      color: #fff;
    }
  }
</style>
@endsection

@section('content')
<div class="row g-6">
  <div class="col-md-12">
    <div class="card shadow-sm border-0">
      <div class="card-header d-flex align-items-center justify-content-between border-bottom py-4 ims-mobile-toolbar">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2 text-primary fw-bold">Daftar Pengguna</h5>
          <small class="text-body-secondary">Kelola Admin, Mentor, dan Intern sistem</small>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary shadow">
          <i class="ri ri-user-add-line me-1"></i> Tambah Pengguna
        </a>
      </div>

      {{-- Filter Bar --}}
      <div class="card-body border-bottom py-3">
        <form method="GET" action="{{ route('admin.users.index') }}" id="filterForm">
          <div class="row g-4 align-items-center">
            {{-- Search --}}
            <div class="col-md-4">
              <div class="form-floating form-floating-outline">
                <input type="text" name="search" class="form-control" id="searchUser"
                  placeholder="Nama atau email..."
                  value="{{ request('search') }}" />
                <label for="searchUser">Cari Nama / Email</label>
              </div>
            </div>

            {{-- Filter Role --}}
            <div class="col-md-3">
              <div class="form-floating form-floating-outline">
                <select name="role" id="filterRole" class="form-select" onchange="document.getElementById('filterForm').submit()">
                  <option value="">Semua Role</option>
                  @foreach($roles as $r)
                  <option value="{{ $r->name }}" {{ request('role') == $r->name ? 'selected' : '' }}>
                    {{ ucfirst($r->name) }}
                  </option>
                  @endforeach
                </select>
                <label for="filterRole">Role</label>
              </div>
            </div>

            {{-- Filter Divisi --}}
            <div class="col-md-3">
              <div class="form-floating form-floating-outline">
                <select name="division_id" id="filterDiv" class="form-select" onchange="document.getElementById('filterForm').submit()">
                  <option value="">Semua Divisi</option>
                  @foreach($divisions as $d)
                  <option value="{{ $d->id }}" {{ request('division_id') == $d->id ? 'selected' : '' }}>
                    {{ $d->name }}
                  </option>
                  @endforeach
                </select>
                <label for="filterDiv">Divisi / Departemen</label>
              </div>
            </div>

            {{-- Actions --}}
            <div class="col-md-2">
              <div class="d-flex gap-2 h-100 align-items-stretch" style="padding-top: 1.5px;">
                <button type="submit" class="btn btn-primary shadow-sm flex-grow-1 px-2">
                  <i class="ri ri-search-line me-1"></i> Cari
                </button>
                @if(request()->hasAny(['search', 'role', 'division_id']))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-danger d-flex align-items-center justify-content-center px-3" title="Reset filter" aria-label="Reset filter">
                  <i class="ri ri-refresh-line"></i>
                </a>
                @endif
              </div>
            </div>
          </div>
        </form>


        {{-- Active filters badge --}}
        @if(request()->hasAny(['search', 'role', 'division_id']))
        <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">
          <small class="text-body-secondary">Filter aktif:</small>
          @if(request('search'))
            <span class="badge bg-label-primary rounded-pill">
              Nama: "{{ request('search') }}"
              <a href="{{ request()->fullUrlWithoutQuery(['search']) }}" class="ms-1 text-primary" aria-label="Hapus filter nama">&times;</a>
            </span>
          @endif
          @if(request('role'))
            <span class="badge bg-label-warning rounded-pill">
              Role: {{ ucfirst(request('role')) }}
              <a href="{{ request()->fullUrlWithoutQuery(['role']) }}" class="ms-1 text-warning" aria-label="Hapus filter role">&times;</a>
            </span>
          @endif
          @if(request('division_id'))
            @php $selectedDiv = $divisions->firstWhere('id', request('division_id')); @endphp
            <span class="badge bg-label-info rounded-pill">
              Divisi: {{ $selectedDiv?->name }}
              <a href="{{ request()->fullUrlWithoutQuery(['division_id']) }}" class="ms-1 text-info" aria-label="Hapus filter divisi">&times;</a>
            </span>
          @endif
          <span class="text-body-secondary small">&mdash; {{ $users->count() }} pengguna ditemukan</span>
        </div>
        @endif
      </div>
      {{-- /Filter Bar --}}

      <div class="card-body p-0">
        <div class="table-responsive ims-card-table-wrap d-none d-md-block">
          <table class="table table-hover mb-0 ims-card-table">
            <thead class="table-light">
              <tr>
                <th class="py-3">Pengguna</th>
                <th class="py-3">Role</th>
                <th class="py-3">Divisi / Departemen</th>
                <th class="py-3">Status</th>
                <th class="py-3 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse($users as $user)
              <tr>
                <td data-label="Pengguna" class="ims-card-primary">
                  <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3">
                      <span class="avatar-initial rounded-circle bg-label-{{ ['primary','success','warning','info','danger'][$loop->index % 5] }}">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                      </span>
                    </div>
                    <div class="d-flex flex-column">
                      <h6 class="mb-0 small fw-bold text-heading">{{ $user->name }}</h6>
                      <small class="text-body-secondary">{{ $user->email }}</small>
                    </div>
                  </div>
                </td>
                <td data-label="Role">
                  @foreach($user->roles as $role)
                  <span class="badge bg-label-{{ $role->name === 'superadmin' ? 'danger' : ($role->name === 'admin' ? 'warning' : ($role->name === 'intern' ? 'success' : 'primary')) }} rounded-pill">
                    {{ ucfirst($role->name) }}
                  </span>
                  @endforeach
                </td>
                <td data-label="Divisi / Departemen">
                  @if($user->division)
                  <div class="d-flex align-items-center">
                    <i class="ri ri-community-line me-2 text-primary"></i>
                    <span class="fw-medium">{{ $user->division->name }}</span>
                  </div>
                  @else
                  <span class="text-body-secondary small fst-italic">Tidak ada divisi</span>
                  @endif
                </td>
                <td data-label="Status">
                  <span class="badge bg-label-success rounded-pill">Aktif</span>
                </td>
                <td data-label="Aksi" class="text-center ims-card-actions">
                  <div class="ims-table-inline-actions">
                    <a href="{{ route('admin.users.edit', $user->id) }}"
                      class="btn btn-sm ims-theme-edit-btn d-inline-flex align-items-center gap-1"
                      title="Edit">
                      <i class="ri ri-pencil-line"></i>
                      <span>Edit</span>
                    </a>
                    @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                      class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1"
                        title="Hapus">
                        <i class="ri ri-delete-bin-line"></i>
                        <span>Hapus</span>
                      </button>
                    </form>
                    @endif
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center py-5">
                  <i class="ri ri-group-line icon-48px text-body-secondary mb-3 d-block"></i>
                  @if(request()->hasAny(['search', 'role', 'division_id']))
                    <p class="mb-1 text-body-secondary">Tidak ada pengguna yang cocok dengan filter.</p>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary mt-2">Reset Filter</a>
                  @else
                    <p class="mb-0 text-body-secondary">Belum ada data pengguna</p>
                  @endif
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div class="d-md-none p-3">
        <div class="users-mobile-shell">
          @forelse($users as $user)
            @php
              $primaryRole = $user->roles->first();
              $roleClass = $primaryRole
                ? ($primaryRole->name === 'superadmin'
                  ? 'danger'
                  : ($primaryRole->name === 'admin'
                    ? 'warning'
                    : ($primaryRole->name === 'intern' ? 'success' : 'primary')))
                : 'secondary';
            @endphp

            <div class="users-mobile-card">
              <div class="card-header border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between gap-3">
                  <div class="min-w-0">
                    <h6 class="users-mobile-name">{{ $user->name }}</h6>
                    <div class="users-mobile-email text-truncate">{{ $user->email }}</div>
                  </div>
                  <span class="badge bg-label-{{ $roleClass }} rounded-pill flex-shrink-0">
                    {{ $primaryRole ? ucfirst($primaryRole->name) : 'Tanpa Role' }}
                  </span>
                </div>
              </div>

              <div class="users-mobile-item">
                <div class="users-mobile-meta-list">
                  <div class="users-mobile-meta-item">
                    <i class="ri ri-community-line"></i>
                    <span>{{ $user->division?->name ?? 'Tidak ada divisi' }}</span>
                  </div>
                  <div class="users-mobile-meta-item">
                    <i class="ri ri-calendar-2-line"></i>
                    <span>Dibuat {{ $user->created_at?->translatedFormat('d M Y') ?? '-' }}</span>
                  </div>
                  <div class="users-mobile-meta-item">
                    <i class="ri ri-checkbox-circle-line text-success"></i>
                    <span>
                      <span class="badge bg-label-success rounded-pill">Aktif</span>
                    </span>
                  </div>
                </div>

                <div class="users-mobile-actions">
                  <a href="{{ route('admin.users.edit', $user->id) }}" class="btn ims-theme-edit-btn d-inline-flex align-items-center justify-content-center gap-1">
                    <i class="ri ri-pencil-line"></i>
                    <span>Edit</span>
                  </a>

                  @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn users-mobile-delete d-inline-flex align-items-center justify-content-center gap-1">
                        <i class="ri ri-delete-bin-line"></i>
                        <span>Hapus</span>
                      </button>
                    </form>
                  @else
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-primary d-inline-flex align-items-center justify-content-center gap-1" style="grid-column: 1 / -1;">
                      <i class="ri ri-pencil-line"></i>
                      <span>Edit</span>
                    </a>
                  @endif
                </div>
              </div>
            </div>
          @empty
            <div class="users-mobile-card text-center p-4">
              <div class="card-body">
                <i class="ri ri-group-line icon-48px text-body-secondary mb-3 d-block"></i>
                @if(request()->hasAny(['search', 'role', 'division_id']))
                  <p class="mb-1 text-body-secondary">Tidak ada pengguna yang cocok dengan filter.</p>
                  <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary mt-2">Reset Filter</a>
                @else
                  <p class="mb-0 text-body-secondary">Belum ada data pengguna</p>
                @endif
              </div>
            </div>
          @endforelse
        </div>
      </div>

      {{-- Footer count --}}
      <div class="card-footer text-body-secondary small py-2 px-3">
        Menampilkan <strong>{{ $users->count() }}</strong> pengguna
      </div>
    </div>
  </div>
</div>

<script>
// Submit search on Enter
document.querySelector('input[name="search"]').addEventListener('keydown', function(e) {
  if (e.key === 'Enter') {
    document.getElementById('filterForm').submit();
  }
});
</script>
@endsection
