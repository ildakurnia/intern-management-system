@extends('layouts/contentNavbarLayout')

@section('title', 'Data Intern')

@section('content')
@include('partials.app-breadcrumb', [
  'items' => [
    ['label' => 'Dashboard', 'url' => route('dashboard.admin')],
    ['label' => 'Data Intern', 'current' => true],
  ],
])

<div class="card">
  <div class="card-header border-bottom">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <h5 class="mb-0">Daftar Peserta Magang</h5>
      <div class="d-flex gap-3 align-items-center">
        <form action="{{ route('admin.interns.index') }}" method="GET" class="d-flex gap-2">
          <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama, institusi..." value="{{ request('search') }}" style="min-width: 200px;">
          <button type="submit" class="btn btn-sm btn-outline-primary"><i class="ri-search-line"></i></button>
          @if(request('search'))
            <a href="{{ route('admin.interns.index') }}" class="btn btn-sm btn-outline-secondary" title="Hapus filter"><i class="ri-close-line"></i></a>
          @endif
        </form>
        @if(auth()->user()->hasAnyRole(['superadmin', 'admin']))
        <a href="{{ route('admin.interns.import') }}" class="btn btn-sm btn-primary">
          <i class="ri-file-excel-line me-1"></i> Import
        </a>
        @endif
      </div>
    </div>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
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
          <td>{{ $interns->firstItem() + $index }}</td>
          <td>
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
          <td>
            <div class="d-flex flex-column">
              <span>{{ $intern->institution_label }}</span>
              <small class="text-muted">{{ $intern->major ?? '-' }}</small>
            </div>
          </td>
          <td>
            <span class="badge bg-label-secondary rounded-pill">{{ $intern->division->name ?? 'Belum ada divisi' }}</span>
          </td>
          <td>
            @if($intern->registration_status === 'approved' && $intern->hasCompletedProfile() && $intern->hasCompletedDocuments())
              <span class="badge bg-label-success rounded-pill">Aktif</span>
            @elseif($intern->registration_status === 'approved')
              <span class="badge bg-label-info rounded-pill">Melengkapi Data</span>
            @else
              <span class="badge bg-label-warning rounded-pill">Register</span>
            @endif
          </td>
          <td>
            <div class="d-flex flex-wrap gap-2">
              @can('admin.interns.approve')
                @if($intern->user_id && $intern->registration_status !== 'approved')
                  <form action="{{ route('admin.interns.approve', $intern->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-sm btn-success">
                      <i class="ri-check-line me-1"></i> Approve
                    </button>
                  </form>
                @endif
              @endcan

              <a href="{{ route('admin.interns.show', $intern->id) }}" class="btn btn-sm btn-outline-primary">
                <i class="ri-eye-line me-1"></i> Detail
              </a>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center py-5">
            <i class="ri-group-line text-muted mb-3" style="font-size: 3rem; display: block;"></i>
            <h5 class="mb-0">Belum Ada Peserta Magang</h5>
            <small class="text-muted">Saat ini belum ada data intern yang terdaftar di divisi ini.</small>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  @if($interns->hasPages())
  <div class="card-footer border-top">
    {{ $interns->links() }}
  </div>
  @endif
</div>
@endsection
