@extends('layouts/contentNavbarLayout')

@section('title', 'Master Lokasi Absensi')

@section('content')
@include('partials.app-breadcrumb', [
  'items' => [
    ['label' => 'Dashboard', 'url' => route('dashboard.admin')],
    ['label' => 'Master Lokasi Absensi', 'current' => true],
  ],
])

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm border-0">
  <div class="card-header border-bottom">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
      <div>
        <h5 class="mb-1">Data Master Lokasi</h5>
        <small class="text-body-secondary">Kelola titik koordinat dan radius lokasi yang boleh dipakai intern untuk absensi.</small>
      </div>
      <div class="d-flex flex-wrap gap-2 ims-mobile-toolbar">
        <form action="{{ route('admin.attendance-locations.index') }}" method="GET" class="d-flex gap-2 ims-mobile-filter ims-inline-search">
          <input
            type="text"
            name="search"
            value="{{ $search }}"
            class="form-control form-control-sm"
            placeholder="Cari nama lokasi..."
            style="min-width: 220px;">
          <button type="submit" class="btn btn-sm btn-outline-primary ims-icon-only-btn" aria-label="Cari lokasi">
            <i class="ri ri-search-line"></i>
          </button>
          @if($search)
            <a href="{{ route('admin.attendance-locations.index') }}" class="btn btn-sm btn-outline-secondary ims-icon-only-btn" aria-label="Reset filter" title="Reset filter">
              <i class="ri ri-refresh-line"></i>
            </a>
          @endif
        </form>
        <a href="{{ route('admin.attendance-locations.create') }}" class="btn btn-primary btn-sm">
          <i class="ri ri-add-line me-1"></i> Tambah Lokasi
        </a>
      </div>
    </div>
  </div>

  <div class="table-responsive ims-card-table-wrap d-none d-md-block">
    <table class="table table-hover mb-0 align-middle ims-card-table">
      <thead class="table-light">
        <tr>
          <th>No</th>
          <th>Nama Lokasi</th>
          <th>Latitude</th>
          <th>Longitude</th>
          <th>Radius</th>
          <th>Intern Aktif</th>
          <th>Keterangan</th>
          <th>Status</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($locations as $index => $location)
          <tr>
            <td data-label="No" class="fw-medium">{{ $locations->firstItem() + $index }}</td>
            <td data-label="Nama Lokasi" class="fw-medium ims-card-primary">{{ $location->name }}</td>
            <td data-label="Latitude">{{ $location->latitude }}</td>
            <td data-label="Longitude">{{ $location->longitude }}</td>
            <td data-label="Radius">{{ $location->radius_meters }} m</td>
            <td data-label="Intern Aktif"><span class="badge bg-label-info rounded-pill">{{ $location->active_interns_count }} intern</span></td>
            <td data-label="Keterangan">{{ $location->notes ?: '-' }}</td>
            <td data-label="Status">
              <span class="badge bg-label-{{ $location->is_active ? 'success' : 'secondary' }} rounded-pill">
                {{ $location->is_active ? 'Aktif' : 'Nonaktif' }}
              </span>
            </td>
            <td data-label="Aksi" class="text-center ims-card-actions">
              <div class="ims-table-inline-actions">
                <a href="{{ route('admin.attendance-locations.edit', $location) }}" class="btn btn-sm btn-warning text-white">
                  <i class="ri ri-pencil-line me-1"></i> Edit
                </a>
                <form action="{{ route('admin.attendance-locations.destroy', $location) }}" method="POST" onsubmit="return confirm('Hapus lokasi ini?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">
                    <i class="ri ri-delete-bin-line me-1"></i> Hapus
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="text-center py-5 text-body-secondary">
              Belum ada master lokasi absensi yang tersimpan.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($locations->hasPages())
    <div class="card-footer border-top">
      {{ $locations->links() }}
    </div>
  @endif
</div>

<div class="d-md-none mt-4">
  <div class="attendance-location-mobile-shell">
    @forelse($locations as $index => $location)
      <div class="attendance-location-mobile-card">
        <div class="attendance-location-mobile-head">
          <div class="min-w-0">
            <div class="attendance-location-mobile-eyebrow">
              <span class="attendance-location-mobile-icon">
                <i class="ri ri-map-pin-line"></i>
              </span>
              <span>Lokasi Absensi</span>
            </div>
            <h6 class="attendance-location-mobile-name">{{ $location->name }}</h6>
            <div class="attendance-location-mobile-sub text-truncate">Titik validasi absensi untuk intern</div>
          </div>
          <span class="badge bg-label-{{ $location->is_active ? 'success' : 'secondary' }} rounded-pill flex-shrink-0">
            {{ $location->is_active ? 'Aktif' : 'Nonaktif' }}
          </span>
        </div>

        <div class="attendance-location-mobile-meta-strip">
          <div class="attendance-location-mobile-meta-pill">
            <span>No</span>
            <strong>{{ $locations->firstItem() + $index }}</strong>
          </div>
          <div class="attendance-location-mobile-meta-pill">
            <span>Intern</span>
            <strong>{{ $location->active_interns_count }} aktif</strong>
          </div>
          <div class="attendance-location-mobile-meta-pill">
            <span>Radius</span>
            <strong>{{ $location->radius_meters }} m</strong>
          </div>
        </div>

        <div class="attendance-location-mobile-grid">
          <div class="attendance-location-mobile-meta-row">
            <span>Latitude</span>
            <strong>{{ $location->latitude }}</strong>
          </div>
          <div class="attendance-location-mobile-meta-row">
            <span>Longitude</span>
            <strong>{{ $location->longitude }}</strong>
          </div>
        </div>

        <div class="attendance-location-mobile-note">
          <span class="attendance-location-mobile-note-label">Keterangan</span>
          <strong class="attendance-location-mobile-note-value">{{ $location->notes ?: 'Belum ada catatan tambahan.' }}</strong>
        </div>

        <div class="attendance-location-mobile-actions">
          <a href="{{ route('admin.attendance-locations.edit', $location) }}" class="btn btn-warning text-white d-inline-flex align-items-center justify-content-center gap-1">
            <i class="ri ri-pencil-line"></i>
            <span>Edit</span>
          </a>
          <form action="{{ route('admin.attendance-locations.destroy', $location) }}" method="POST" onsubmit="return confirm('Hapus lokasi ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger d-inline-flex align-items-center justify-content-center gap-1 w-100">
              <i class="ri ri-delete-bin-line"></i>
              <span>Hapus</span>
            </button>
          </form>
        </div>

        <div class="attendance-location-mobile-foot">
          <span class="badge bg-label-info rounded-pill">{{ $location->active_interns_count }} intern aktif</span>
          <span class="attendance-location-mobile-foot-text">Siap dipakai untuk absensi hari ini</span>
        </div>
      </div>
    @empty
      <div class="attendance-location-mobile-card text-center p-4">
        <div class="card-body">
          <i class="ri ri-map-pin-line icon-48px text-body-secondary mb-3 d-block"></i>
          <p class="mb-0 text-body-secondary">Belum ada master lokasi absensi yang tersimpan.</p>
        </div>
      </div>
    @endforelse

    @if($locations->hasPages())
      <div class="d-flex justify-content-center pt-2">
        {{ $locations->links('pagination::bootstrap-5') }}
      </div>
    @endif
  </div>
</div>

<style>
  .attendance-location-mobile-shell {
    display: grid;
    gap: 1rem;
  }

  .attendance-location-mobile-card {
    padding: 1rem;
    border: 1px solid var(--bs-border-color);
    border-radius: 1rem;
    background: var(--bs-card-bg);
    overflow: hidden;
    box-shadow: 0 0.45rem 1.1rem rgba(15, 23, 42, 0.06);
    display: grid;
    gap: 0.9rem;
  }

  .attendance-location-mobile-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.75rem;
  }

  .attendance-location-mobile-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    margin-bottom: 0.35rem;
    color: var(--bs-secondary-color);
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    font-weight: 700;
  }

  .attendance-location-mobile-icon {
    width: 1.8rem;
    height: 1.8rem;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--bs-tertiary-bg);
    color: var(--bs-primary);
    flex-shrink: 0;
  }

  .attendance-location-mobile-name {
    margin: 0;
    color: var(--bs-heading-color);
    font-size: 1rem;
    font-weight: 800;
    line-height: 1.25;
  }

  .attendance-location-mobile-sub {
    color: var(--bs-secondary-color);
    font-size: 0.875rem;
  }

  .attendance-location-mobile-meta-strip {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 0.55rem;
  }

  .attendance-location-mobile-meta-pill {
    padding: 0.75rem 0.8rem;
    border-radius: 0.9rem;
    border: 1px solid rgba(17, 24, 39, 0.18);
    background: #fff;
    min-width: 0;
  }

  .attendance-location-mobile-meta-pill span,
  .attendance-location-mobile-note-label,
  .attendance-location-mobile-foot-text {
    display: block;
    color: #111827;
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    font-weight: 700;
  }

  .attendance-location-mobile-meta-pill strong {
    display: block;
    margin-top: 0.25rem;
    color: #111827;
    font-size: 0.9rem;
    font-weight: 700;
    line-height: 1.3;
    word-break: break-word;
  }

  .attendance-location-mobile-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.65rem;
  }

  .attendance-location-mobile-meta-row {
    padding: 0.85rem;
    border-radius: 0.9rem;
    border: 1px solid rgba(17, 24, 39, 0.18);
    background: #fff;
    min-width: 0;
  }

  .attendance-location-mobile-meta-row span {
    color: #111827;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }

  .attendance-location-mobile-meta-row strong {
    color: #111827;
    font-size: 0.95rem;
    font-weight: 700;
    word-break: break-word;
  }

  .attendance-location-mobile-note {
    padding: 0.9rem;
    border-radius: 0.9rem;
    border: 1px solid rgba(17, 24, 39, 0.18);
    background: #fff;
  }

  .attendance-location-mobile-note-value {
    display: block;
    margin-top: 0.25rem;
    color: #111827;
    font-size: 0.94rem;
    font-weight: 600;
    line-height: 1.45;
  }

  .attendance-location-mobile-actions {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.6rem;
  }

  .attendance-location-mobile-actions .btn,
  .attendance-location-mobile-actions form {
    width: 100%;
  }

  .attendance-location-mobile-actions .btn {
    min-height: 2.75rem;
    border-radius: 0.85rem;
  }

  .attendance-location-mobile-foot {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.55rem;
    padding-top: 0.1rem;
  }

  .attendance-location-mobile-foot .badge {
    border-radius: 999px;
  }

  @media (max-width: 767.98px) {
    .card.shadow-sm.border-0 {
      border-radius: 1rem;
      box-shadow: 0 0.45rem 1.1rem rgba(15, 23, 42, 0.06);
    }

    .card.shadow-sm.border-0 .card-header,
    .card.shadow-sm.border-0 .card-footer {
      background: transparent;
    }

    .card.shadow-sm.border-0 .table-responsive {
      display: none;
    }
  }
</style>
@endsection
