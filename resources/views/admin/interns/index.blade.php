@extends('layouts/contentNavbarLayout')

@section('title', 'Data Intern')

@section('page-style')
<style>
  @media (min-width: 768px) {
    .content-wrapper > .container-xxl.container-p-y,
    .content-wrapper > .container-fluid.container-p-y {
      max-width: none !important;
      width: 100% !important;
      padding-left: 1rem !important;
      padding-right: 1rem !important;
    }
  }

  @media (max-width: 767.98px) {
    .intern-mobile-shell {
      display: grid;
      gap: 1rem;
    }

    .intern-mobile-card {
      border: 1px solid var(--bs-border-color);
      border-radius: 1rem;
      background: var(--bs-card-bg);
      overflow: hidden;
    }

    .intern-mobile-card .card-body {
      padding: 1rem;
    }

    .intern-mobile-card .intern-mobile-title {
      margin: 0 0 0.25rem;
      color: var(--bs-heading-color);
      font-size: 1rem;
      font-weight: 700;
      line-height: 1.25;
    }

    .intern-mobile-card .intern-mobile-email,
    .intern-mobile-card .intern-mobile-meta {
      color: var(--bs-secondary-color);
      font-size: 0.875rem;
    }

    .intern-mobile-card .intern-mobile-meta-list {
      display: grid;
      gap: 0.4rem;
      margin-top: 0.9rem;
    }

    .intern-mobile-card .intern-mobile-meta-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: var(--bs-body-color);
      font-size: 0.875rem;
      line-height: 1.35;
    }

    .intern-mobile-card .intern-mobile-divider {
      margin: 1rem 0 0.85rem;
    }

    .intern-mobile-card .intern-mobile-actions {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 0.5rem;
    }

    .intern-mobile-card .intern-mobile-actions .btn {
      width: 100%;
      border-radius: 0.75rem;
    }

    .intern-mobile-card .intern-mobile-actions .is-full {
      grid-column: 1 / -1;
    }
  }
</style>
@endsection

@section('content')
@include('partials.app-breadcrumb', [
  'items' => [
    ['label' => 'Dashboard', 'url' => route('dashboard.admin')],
    ['label' => 'Data Intern', 'current' => true],
  ],
])

<div class="card">
  <div class="card-header border-bottom">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 ims-mobile-toolbar">
      <h5 class="mb-0">Daftar Peserta Magang</h5>
      <div class="d-flex gap-3 align-items-center ims-mobile-toolbar">
        <form action="{{ route('admin.interns.index') }}" method="GET" class="d-flex gap-2 ims-mobile-filter ims-inline-search">
          <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama, institusi..." value="{{ request('search') }}" style="min-width: 200px;">
          <button type="submit" class="btn btn-sm btn-outline-primary ims-icon-only-btn" aria-label="Cari intern">
            <i class="ri ri-search-line"></i>
          </button>
          @if(request('search'))
            <a href="{{ route('admin.interns.index') }}" class="btn btn-sm btn-outline-secondary ims-icon-only-btn" title="Hapus filter" aria-label="Hapus filter">
              <i class="ri ri-refresh-line"></i>
            </a>
          @endif
        </form>
        @if(auth()->user()->hasAnyRole(['superadmin', 'admin']))
        <a href="{{ route('admin.interns.import') }}" class="btn btn-sm btn-primary">
          <i class="ri ri-file-excel-line me-1"></i> Import
        </a>
        @endif
      </div>
    </div>
  </div>
  <div class="table-responsive ims-card-table-wrap d-none d-md-block">
    <table class="table table-hover ims-card-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Profil Peserta</th>
          <th>Asal Institusi</th>
          <th>Penempatan</th>
          <th>Onboarding</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse($interns as $index => $intern)
        <tr>
          <td data-label="#" class="fw-medium">{{ $interns->firstItem() + $index }}</td>
          <td data-label="Profil Peserta" class="ims-card-primary">
            <div class="d-flex align-items-center">
              <div class="avatar avatar-sm me-3">
                @if($intern->photo)
                  <img src="{{ asset('storage/' . $intern->photo) }}" alt="Avatar" class="rounded-circle" style="object-fit: cover; width: 100%; height: 100%;">
                @else
                  <span class="avatar-initial rounded-circle bg-label-primary">{{ strtoupper(substr($intern->user->name ?? $intern->name, 0, 2)) }}</span>
                @endif
              </div>
              <div class="d-flex flex-column">
                <span class="fw-medium">{{ $intern->user->name ?? $intern->name }}</span>
                <small class="text-muted">{{ $intern->user->email ?? $intern->email }}</small>
              </div>
            </div>
          </td>
          <td data-label="Asal Institusi">
            <div class="d-flex flex-column">
              <span>{{ $intern->institution_label }}</span>
              <small class="text-muted">{{ $intern->major ?? '-' }}</small>
            </div>
          </td>
          <td data-label="Penempatan">
            <span class="badge bg-label-secondary rounded-pill">{{ $intern->division->name ?? 'Belum ada divisi' }}</span>
          </td>
          <td data-label="Onboarding">
            @if($intern->status === 'completed')
              <span class="badge bg-label-secondary rounded-pill">Selesai</span>
            @elseif($intern->status === 'terminated')
              <span class="badge bg-label-danger rounded-pill">Dihentikan</span>
            @elseif($intern->registration_status === 'approved' && $intern->hasCompletedProfile() && $intern->hasCompletedDocuments())
              <span class="badge bg-label-success rounded-pill">Aktif</span>
            @elseif($intern->registration_status === 'approved')
              <span class="badge bg-label-info rounded-pill">Melengkapi Data</span>
            @else
              <span class="badge bg-label-warning rounded-pill">Register</span>
            @endif
          </td>
          <td data-label="Aksi" class="ims-card-actions">
            <div class="ims-table-inline-actions">
              @can('admin.interns.approve')
                @if($intern->user_id && $intern->registration_status !== 'approved')
                  <form action="{{ route('admin.interns.approve', $intern->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-sm btn-success">
                      <i class="ri ri-check-line me-1"></i> Approve
                    </button>
                  </form>
                @endif
              @endcan

              <a href="{{ route('admin.interns.show', $intern->id) }}" class="btn btn-sm btn-outline-primary">
                <i class="ri ri-eye-line me-1"></i> Detail
              </a>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center py-5">
            <i class="ri ri-group-line text-muted mb-3" style="font-size: 3rem; display: block;"></i>
            <h5 class="mb-0">Belum Ada Peserta Magang</h5>
            <small class="text-muted">Saat ini belum ada data intern yang terdaftar di divisi ini.</small>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="d-md-none p-3">
    <div class="intern-mobile-shell">
      @forelse($interns as $index => $intern)
        @php
          $displayName = $intern->user?->name ?? $intern->name;
          $displayEmail = $intern->user?->email ?? $intern->email ?? '-';
          $displayPhone = $intern->phone ?? '-';
          $onboardingBadge = 'bg-label-warning';
          $onboardingLabel = 'Register';

          if ($intern->status === 'completed') {
            $onboardingBadge = 'bg-label-secondary';
            $onboardingLabel = 'Selesai';
          } elseif ($intern->status === 'terminated') {
            $onboardingBadge = 'bg-label-danger';
            $onboardingLabel = 'Dihentikan';
          } elseif ($intern->registration_status === 'approved' && $intern->hasCompletedProfile() && $intern->hasCompletedDocuments()) {
            $onboardingBadge = 'bg-label-success';
            $onboardingLabel = 'Aktif';
          } elseif ($intern->registration_status === 'approved') {
            $onboardingBadge = 'bg-label-info';
            $onboardingLabel = 'Melengkapi Data';
          }
        @endphp

        <div class="intern-mobile-card">
          <div class="card-body">
            <div class="d-flex align-items-start justify-content-between gap-3">
              <div class="min-w-0">
                <h6 class="intern-mobile-title">{{ $displayName }}</h6>
                <div class="intern-mobile-email text-truncate">{{ $displayEmail }}</div>
              </div>
              <span class="badge bg-label-primary rounded-pill flex-shrink-0">{{ $intern->division->name ?? 'Belum ada divisi' }}</span>
            </div>

            <div class="intern-mobile-meta-list">
              <div class="intern-mobile-meta-item">
                <i class="ri ri-phone-line"></i>
                <span>HP: {{ $displayPhone }}</span>
              </div>
              <div class="intern-mobile-meta-item">
                <i class="ri ri-building-2-line"></i>
                <span>{{ $intern->institution_label }}</span>
              </div>
              <div class="intern-mobile-meta-item">
                <i class="ri ri-book-open-line"></i>
                <span>{{ $intern->major ?? '-' }}</span>
              </div>
              <div class="intern-mobile-meta-item">
                <i class="ri ri-shield-check-line"></i>
                <span>Status: <span class="badge {{ $onboardingBadge }} rounded-pill">{{ $onboardingLabel }}</span></span>
              </div>
            </div>

            <hr class="intern-mobile-divider">

            <div class="intern-mobile-actions">
              @can('admin.interns.approve')
                @if($intern->user_id && $intern->registration_status !== 'approved')
                  <form action="{{ route('admin.interns.approve', $intern->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">
                      <i class="ri ri-check-line me-1"></i> Approve
                    </button>
                  </form>
                @endif
              @endcan

              <a href="{{ route('admin.interns.show', $intern->id) }}" class="btn btn-outline-primary {{ ($intern->user_id && $intern->registration_status !== 'approved') ? '' : 'is-full' }}">
                <i class="ri ri-eye-line me-1"></i> Detail
              </a>
            </div>
          </div>
        </div>
      @empty
        <div class="intern-mobile-card text-center p-4">
          <div class="card-body">
            <i class="ri ri-group-line text-muted mb-3" style="font-size: 3rem; display: block;"></i>
            <h5 class="mb-0">Belum Ada Peserta Magang</h5>
            <small class="text-muted">Saat ini belum ada data intern yang terdaftar di divisi ini.</small>
          </div>
        </div>
      @endforelse
    </div>
  </div>
  
  @if($interns->hasPages())
  <div class="card-footer border-top">
    {{ $interns->links() }}
  </div>
  @endif
</div>
@endsection
