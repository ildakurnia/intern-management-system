@extends('layouts/contentNavbarLayout')

@section('title', 'Manajemen Divisi')

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

  .divisions-desktop-code-row,
  .divisions-desktop-title-row,
  .divisions-desktop-stat {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
  }

  .divisions-desktop-icon {
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

  .divisions-desktop-icon .icon-base {
    font-size: 1rem;
  }

  .divisions-desktop-name-wrap {
    gap: 0.2rem;
  }

  .divisions-desktop-desc {
    max-width: 250px;
    color: var(--bs-secondary-color);
    font-size: 0.8125rem;
    line-height: 1.45;
  }

  .divisions-desktop-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    white-space: nowrap;
  }

  @media (max-width: 767.98px) {
    .divisions-mobile-shell {
      display: grid;
      gap: 1rem;
    }

    .divisions-mobile-card {
      border: 1px solid var(--bs-border-color);
      border-radius: 1rem;
      background: var(--bs-card-bg);
      box-shadow: 0 12px 28px rgba(47, 43, 61, 0.12);
      color: var(--bs-body-color);
      overflow: hidden;
    }

    .divisions-mobile-card .card-body {
      padding: 1rem;
    }

    .divisions-mobile-card .divisions-mobile-head {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 0.75rem;
    }

    .divisions-mobile-card .divisions-mobile-title {
      margin: 0;
      color: var(--bs-heading-color);
      font-size: 1rem;
      font-weight: 700;
    }

    .divisions-mobile-card .divisions-mobile-title-wrap {
      display: flex;
      align-items: center;
      gap: 0.55rem;
      min-width: 0;
    }

    .divisions-mobile-card .divisions-mobile-title-icon {
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

    .divisions-mobile-card .divisions-mobile-title-icon .icon-base {
      font-size: 1rem;
    }

    .divisions-mobile-card .divisions-mobile-code {
      border-radius: 999px;
      background: var(--bs-primary-bg-subtle);
      color: var(--bs-primary-text-emphasis);
      font-size: 0.75rem;
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      white-space: nowrap;
    }

    .divisions-mobile-card .divisions-mobile-subtitle {
      color: var(--bs-secondary-color);
      font-size: 0.875rem;
    }

    .divisions-mobile-card .divisions-mobile-desc {
      color: var(--bs-body-color);
      font-size: 0.82rem;
      margin-top: 0.5rem;
      line-height: 1.5;
    }

    .divisions-mobile-card .divisions-mobile-stats {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 0.5rem;
      margin-top: 1rem;
    }

    .divisions-mobile-card .divisions-mobile-stat {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.4rem;
      text-align: center;
      border-radius: 999px;
      white-space: nowrap;
    }

    .divisions-mobile-card .divisions-mobile-status-chip {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      padding: 0.35rem 0.7rem;
      border-radius: 999px;
      font-size: 0.75rem;
      font-weight: 700;
      white-space: nowrap;
    }

    .divisions-mobile-card .divisions-mobile-actions {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 0.5rem;
      margin-top: 1rem;
    }

    .divisions-mobile-card .divisions-mobile-actions .btn {
      width: 100%;
      border-radius: 0.75rem;
    }

    .divisions-mobile-card .divisions-mobile-delete {
      background: var(--bs-danger);
      border-color: var(--bs-danger);
      color: #fff;
    }
  }
</style>
@endsection

@section('content')
<div class="row g-6">
  <div class="col-12">
    <div class="card shadow-sm border-0">
      <div class="card-header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 py-4 ims-mobile-toolbar">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2 text-primary fw-bold">Daftar Divisi / Departemen</h5>
          <small class="text-body-secondary">Kelola struktur organisasi Persero Batam</small>
        </div>
        <a href="{{ route('admin.divisions.create') }}" class="btn btn-primary shadow-sm text-nowrap">
          <i class="ri ri-add-line me-1"></i> Tambah Divisi
        </a>
      </div>

      <div class="card-body border-bottom py-3">
        <form method="GET" action="{{ route('admin.divisions.index') }}" id="divisionFilterForm">
          <div class="row g-4 align-items-center">
            <div class="col-md-5">
              <div class="form-floating form-floating-outline">
                <input
                  type="text"
                  name="search"
                  class="form-control"
                  id="searchDivision"
                  placeholder="Cari kode atau nama divisi..."
                  value="{{ $search }}"
                />
                <label for="searchDivision">Cari Kode / Nama</label>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-floating form-floating-outline">
                <select name="status" id="filterStatus" class="form-select" onchange="document.getElementById('divisionFilterForm').submit()">
                  <option value="">Semua Status</option>
                  <option value="active" @selected($status === 'active')>Aktif</option>
                  <option value="inactive" @selected($status === 'inactive')>Non-aktif</option>
                </select>
                <label for="filterStatus">Status</label>
              </div>
            </div>

            <div class="col-md-3">
              <div class="d-flex gap-2 h-100 align-items-stretch" style="padding-top: 1.5px;">
                <button type="submit" class="btn btn-primary shadow-sm flex-grow-1 px-2">
                  <i class="ri ri-search-line me-1"></i> Cari
                </button>
                @if(request()->hasAny(['search', 'status']))
                  <a href="{{ route('admin.divisions.index') }}" class="btn btn-outline-danger d-flex align-items-center justify-content-center px-3" title="Reset filter" aria-label="Reset filter">
                    <i class="ri ri-refresh-line"></i>
                  </a>
                @endif
              </div>
            </div>
          </div>
        </form>

        @if(request()->hasAny(['search', 'status']))
          <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">
            <small class="text-body-secondary">Filter aktif:</small>
            @if($search)
              <span class="badge bg-label-primary rounded-pill">
                Pencarian: "{{ $search }}"
                <a href="{{ request()->fullUrlWithoutQuery(['search']) }}" class="ms-1 text-primary" aria-label="Hapus filter pencarian">&times;</a>
              </span>
            @endif
            @if($status)
              <span class="badge bg-label-info rounded-pill">
                Status: {{ $status === 'active' ? 'Aktif' : 'Non-aktif' }}
                <a href="{{ request()->fullUrlWithoutQuery(['status']) }}" class="ms-1 text-info" aria-label="Hapus filter status">&times;</a>
              </span>
            @endif
            <span class="text-body-secondary small">&mdash; {{ $divisions->count() }} divisi ditemukan</span>
          </div>
        @endif
      </div>

      <div class="card-body p-0 d-none d-md-block">
        <div class="table-responsive ims-card-table-wrap p-0 p-md-2">
          <table class="table table-hover mb-0 ims-card-table">
            <thead class="table-light">
              <tr>
                <th class="py-3">Kode</th>
                <th class="py-3">Nama Divisi</th>
                <th class="py-3 text-center">Total Intern</th>
                <th class="py-3 text-center">Total Mentor</th>
                <th class="py-3 text-center">Status</th>
                <th class="py-3 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse($divisions as $div)
                <tr>
                  <td data-label="Kode">
                    <div class="divisions-desktop-code-row">
                      <span class="divisions-desktop-icon">
                        <i class="icon-base ri ri-hashtag"></i>
                      </span>
                      <span class="fw-bold text-primary">{{ $div->code }}</span>
                    </div>
                  </td>
                  <td data-label="Nama Divisi">
                    <div class="d-flex flex-column align-items-md-start align-items-end divisions-desktop-name-wrap">
                      <div class="divisions-desktop-title-row">
                        <span class="divisions-desktop-icon">
                          <i class="icon-base ri ri-community-line"></i>
                        </span>
                        <h6 class="mb-0 fw-bold">{{ $div->name }}</h6>
                      </div>
                      @if($div->description)
                        <small class="divisions-desktop-desc text-wrap text-md-truncate mt-1 text-md-start text-end">{{ $div->description }}</small>
                      @endif
                    </div>
                  </td>
                  <td data-label="Total Intern" class="text-md-center">
                    <span class="badge bg-label-info rounded-pill divisions-desktop-badge">
                      <i class="icon-base ri ri-user-3-line"></i>
                      <span>{{ $div->interns_count }} Intern</span>
                    </span>
                  </td>
                  <td data-label="Total Mentor" class="text-md-center">
                    <span class="badge bg-label-primary rounded-pill divisions-desktop-badge">
                      <i class="icon-base ri ri-user-star-line"></i>
                      <span>{{ $div->mentors_count }} Mentor</span>
                    </span>
                  </td>
                  <td data-label="Status" class="text-md-center">
                    <span class="badge bg-label-{{ $div->is_active ? 'success' : 'danger' }} rounded-pill divisions-desktop-badge">
                      <i class="icon-base ri {{ $div->is_active ? 'ri-checkbox-circle-line' : 'ri-close-circle-line' }}"></i>
                      {{ $div->is_active ? 'Aktif' : 'Non-aktif' }}
                    </span>
                  </td>
                  <td data-label="Aksi" class="text-center actions-cell">
                    <div class="d-flex justify-content-end justify-content-md-center gap-2">
                      <a href="{{ route('admin.divisions.edit', $div->id) }}" class="btn btn-sm ims-theme-edit-btn d-inline-flex align-items-center gap-1 shadow-none ims-division-edit-btn" title="Edit">
                        <i class="ri ri-pencil-line"></i>
                        <span>Edit</span>
                      </a>
                      <form action="{{ route('admin.divisions.destroy', $div->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus divisi ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 shadow-none ims-division-delete-btn" title="Hapus">
                          <i class="ri ri-delete-bin-line"></i>
                          <span>Hapus</span>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center py-4 text-body-secondary">Belum ada data divisi.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div class="card-body d-md-none">
        <div class="divisions-mobile-shell">
          @forelse($divisions as $div)
            <div class="divisions-mobile-card">
              <div class="card-body">
                <div class="divisions-mobile-head">
                  <div class="min-w-0">
                    <div class="divisions-mobile-title-wrap">
                      <span class="divisions-mobile-title-icon">
                        <i class="icon-base ri ri-community-line"></i>
                      </span>
                      <h6 class="divisions-mobile-title text-truncate">{{ $div->name }}</h6>
                    </div>
                    <div class="divisions-mobile-subtitle mt-1">Kode: <strong>{{ $div->code }}</strong></div>
                    @if($div->description)
                      <div class="divisions-mobile-desc">{{ $div->description }}</div>
                    @endif
                  </div>
                  <span class="badge divisions-mobile-status-chip bg-label-{{ $div->is_active ? 'success' : 'danger' }}">
                    {{ $div->is_active ? 'Aktif' : 'Non-aktif' }}
                  </span>
                </div>

                <div class="divisions-mobile-stats">
                  <span class="badge bg-label-info rounded-pill divisions-mobile-stat">
                    <i class="icon-base ri ri-user-3-line"></i>
                    <span>{{ $div->interns_count }} Intern</span>
                  </span>
                  <span class="badge bg-label-primary rounded-pill divisions-mobile-stat">
                    <i class="icon-base ri ri-user-star-line"></i>
                    <span>{{ $div->mentors_count }} Mentor</span>
                  </span>
                </div>

                <div class="divisions-mobile-actions">
                  <a href="{{ route('admin.divisions.edit', $div->id) }}" class="btn divisions-mobile-edit ims-theme-edit-btn d-inline-flex align-items-center justify-content-center gap-1">
                    <i class="ri ri-pencil-line"></i>
                    <span>Edit</span>
                  </a>
                  <form action="{{ route('admin.divisions.destroy', $div->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus divisi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn divisions-mobile-delete d-inline-flex align-items-center justify-content-center gap-1">
                      <i class="ri ri-delete-bin-line"></i>
                      <span>Hapus</span>
                    </button>
                  </form>
                </div>
              </div>
            </div>
          @empty
            <div class="divisions-mobile-card text-center p-4">
              <div class="card-body">
                <i class="ri ri-community-line icon-32px text-muted mb-2 d-block"></i>
                <p class="mb-0">Belum ada data divisi.</p>
              </div>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
