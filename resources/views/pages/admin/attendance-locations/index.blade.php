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

  <div class="table-responsive ims-card-table-wrap">
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
@endsection
