@extends('layouts/contentNavbarLayout')

@section('title', 'Manajemen Role')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
         'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss'])
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'])
@endsection

@section('page-style')
<style>
/* ── Permission Toggle Panel ─────────────────────────── */
.permission-table { width: 100%; border-collapse: collapse; }
.permission-table th {
  padding: 10px 14px;
  background: rgba(var(--bs-primary-rgb), 0.08);
  color: var(--bs-heading-color);
  font-weight: 600;
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: .05em;
  position: sticky;
  top: 0;
  z-index: 1;
}
.permission-table td { padding: 8px 14px; vertical-align: middle; border-bottom: 1px solid var(--bs-border-color); }
.permission-table tr:last-child td { border-bottom: none; }
.permission-group-header td {
  background: rgba(var(--bs-primary-rgb), 0.05);
  font-weight: 600;
  color: var(--bs-primary);
  padding: 10px 14px;
  border-top: 2px solid rgba(var(--bs-primary-rgb), 0.15);
}
.permission-group-block { display: none; }
.permission-group-block.active { display: table-row-group; }

/* Toggle switch */
.form-check-input[type=checkbox].form-check-input {
  cursor: pointer;
}
.permission-select-all { display: flex; align-items: center; gap: 8px; }

/* Pagination bar */
.perm-pagination {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 12px;
  padding: 8px 4px;
  border-top: 1px solid var(--bs-border-color);
}
.perm-pagination .page-info { font-size: 0.85rem; color: var(--bs-body-color); }
.perm-pagination .btn-perm-page {
  border: 1px solid var(--bs-border-color);
  background: var(--bs-body-bg);
  color: var(--bs-body-color);
  border-radius: 6px;
  padding: 4px 14px;
  font-size: 0.85rem;
  cursor: pointer;
  transition: all .2s;
}
.perm-pagination .btn-perm-page:disabled { opacity: 0.4; cursor: not-allowed; }
.perm-pagination .btn-perm-page:not(:disabled):hover {
  background: rgba(var(--bs-primary-rgb), 0.1);
  border-color: var(--bs-primary);
  color: var(--bs-primary);
}
</style>
@endsection

@section('content')

  <h4 class="mb-1">Daftar Role</h4>
  <p class="mb-6">Setiap role memberikan akses ke menu dan fitur yang sudah ditentukan, sehingga pengguna hanya bisa mengakses apa yang sesuai dengan perannya.</p>

  {{-- Role Cards --}}
  <div class="row g-6">

    {{-- Dynamic Role Cards --}}
    @foreach ($roles as $index => $role)
    @php
      $avatars = ['5.png','12.png','6.png','9.png','1.png','4.png','2.png','3.png','15.png','10.png'];
      $userCount = $role->users->count();
      $shownAvatars = array_slice($avatars, 0, min(3, $userCount));
      $extra = max(0, $userCount - 3);
      $rolePerms = $role->permissions->pluck('name')->toArray();
    @endphp
    <div class="col-xl-4 col-lg-6 col-md-6">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <p class="mb-0">Total {{ $userCount }} pengguna</p>
            <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
              @foreach ($shownAvatars as $avatar)
              <li class="avatar pull-up" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $role->name }}">
                <img class="rounded-circle" src="{{ asset('assets/img/avatars/' . $avatar) }}" alt="Avatar" />
              </li>
              @endforeach
              @if ($extra > 0)
              <li class="avatar">
                <span class="avatar-initial rounded-circle pull-up bg-lightest text-body"
                  data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $extra }} lainnya">+{{ $extra }}</span>
              </li>
              @endif
            </ul>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <div class="role-heading">
              <h5 class="mb-1">{{ ucfirst($role->name) }}</h5>
              <a href="{{ route('roles.edit', $role->id) }}">
                <p class="mb-0">Edit Role &rsaquo;</p>
              </a>
            </div>
            <form action="{{ route('roles.destroy', $role) }}" method="POST"
              onsubmit="return confirm('Hapus role {{ $role->name }}?');" class="d-inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-icon btn-text-secondary rounded-pill">
                <i class="icon-base ri ri-delete-bin-line icon-22px text-danger"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    @endforeach

    {{-- Add New Role Card --}}
    <div class="col-xl-4 col-lg-6 col-md-6">
      <div class="card h-100">
        <div class="row h-100">
          <div class="col-5">
            <div class="d-flex align-items-end h-100 justify-content-center mt-5">
              <img src="{{ asset('assets/img/illustrations/add-new-role-illustration.png') }}" class="img-fluid"
                alt="add role" width="68" />
            </div>
          </div>
          <div class="col-7">
            <div class="card-body text-sm-end text-center ps-sm-0">
              <a href="{{ route('roles.create') }}"
                class="btn btn-sm btn-primary mb-4 text-nowrap">Tambah Role</a>
              <p class="mb-0">Tambah role baru<br />jika belum ada.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Table Section --}}
    <div class="col-12">
      <h4 class="mt-6 mb-1">Semua Pengguna &amp; Role Mereka</h4>
      <p class="mb-6">Lihat seluruh akun pengguna sistem beserta role yang diberikan kepada mereka.</p>
    </div>
    <div class="col-12">
      <div class="card">
        <div class="table-responsive">
          <table class="table table-hover" id="rolesTable">
            <thead>
              <tr>
                <th>#</th>
                <th>Role</th>
                <th>Guard</th>
                <th>Total Pengguna</th>
                <th>Dibuat</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($roles as $role)
              @php $rolePerms = $role->permissions->pluck('name')->toArray(); @endphp
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                  <span class="d-flex align-items-center gap-2">
                    <span class="avatar-initial rounded bg-label-primary p-2">
                      <i class="icon-base ri ri-shield-user-line icon-18px"></i>
                    </span>
                    <strong>{{ ucfirst($role->name) }}</strong>
                  </span>
                </td>
                <td><span class="badge bg-label-secondary">{{ $role->guard_name }}</span></td>
                <td>{{ $role->users->count() }} pengguna</td>
                <td>{{ $role->created_at?->format('d M Y') ?? '-' }}</td>
                <td>
                  <div class="d-flex align-items-center gap-1">
                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-icon btn-text-secondary btn-sm rounded-pill"
                      title="Edit">
                      <i class="icon-base ri ri-edit-line icon-20px"></i>
                    </a>
                    <form action="{{ route('roles.destroy', $role) }}" method="POST"
                      onsubmit="return confirm('Hapus role {{ $role->name }}?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-icon btn-text-secondary btn-sm rounded-pill" title="Hapus">
                        <i class="icon-base ri ri-delete-bin-line icon-20px text-danger"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
  {{-- /Role cards --}}

  </div>
  {{-- /Role cards --}}

@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Initialization of table row highlighting or other minor features
});
</script>
@endsection
