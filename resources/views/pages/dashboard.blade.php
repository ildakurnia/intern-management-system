@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/apex-charts/apex-charts.scss',
])
@endsection

@section('page-style')
@vite('resources/assets/vendor/scss/pages/cards-statistics.scss')
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/apex-charts/apexcharts.js',
])
@endsection

@section('page-script')
  @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('content')
<div class="row g-3 g-xl-6 mb-3 mb-xl-6">

  {{-- Selamat Datang Card --}}
  <div class="col-12 col-xxl-4">
    <div class="card h-100">
      <div class="card-body text-wrap">
        <h5 class="card-title mb-1">Selamat datang, <span class="fw-bold">{{ auth()->user()->name }}!</span> 🎉</h5>
        <p class="card-subtitle mb-3">{{ $pageDescription ?? 'Portal Intern Management System' }}</p>
        <h4 class="text-primary mb-0">{{ $totalInterns ?? 0 }} Intern</h4>
        <p class="mb-3">Aktif bulan ini 🚀</p>
        @if(auth()->user()->hasAnyRole(['superadmin', 'admin']))
        <a href="{{ route('admin.logbooks.index') }}" class="btn btn-sm btn-primary">Lihat Logbook</a>
        @endif
      </div>
      <img src="{{ asset('assets/img/illustrations/trophy.png') }}"
        class="position-absolute bottom-0 end-0 me-4 d-none d-sm-block" height="140" alt="trophy" />
      <img src="{{ asset('assets/img/illustrations/trophy.png') }}"
        class="position-absolute bottom-0 end-0 me-2 d-sm-none" height="80" alt="trophy" style="opacity: 0.5;" />
    </div>
  </div>

  {{-- Total Intern --}}
  <div class="col-6 col-md-3 col-xxl-2">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
          <div class="avatar">
            <div class="avatar-initial bg-label-primary rounded-3">
              <i class="icon-base ri ri-group-line icon-24px"></i>
            </div>
          </div>
          <div class="d-flex align-items-center">
            <p class="mb-0 text-success">Aktif</p>
            <i class="icon-base ri ri-arrow-up-s-line text-success icon-sm"></i>
          </div>
        </div>
        <div class="card-info mt-3 mt-sm-5">
          <h5 class="mb-1">{{ $totalInterns ?? 0 }}</h5>
          <small class="d-block text-truncate mb-1">Total Anak Magang</small>
          <div class="badge bg-label-secondary rounded-pill text-truncate" style="max-width: 100%;">Semua Divisi</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Total Logbook --}}
  <div class="col-6 col-md-3 col-xxl-2">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
          <div class="avatar">
            <div class="avatar-initial bg-label-success rounded-3">
              <i class="icon-base ri ri-draft-line icon-24px"></i>
            </div>
          </div>
          <div class="d-flex align-items-center">
            <p class="mb-0 text-success">+{{ $logbookThisMonth ?? 0 }}</p>
            <i class="icon-base ri ri-arrow-up-s-line text-success icon-sm"></i>
          </div>
        </div>
        <div class="card-info mt-3 mt-sm-5">
          <h5 class="mb-1">{{ $totalLogbooks ?? 0 }}</h5>
          <small class="d-block text-truncate mb-1">Total Logbook</small>
          <div class="badge bg-label-secondary rounded-pill text-truncate" style="max-width: 100%;">Bulan Ini</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Total Role --}}
  <div class="col-6 col-md-3 col-xxl-2">
    <div class="card h-100">
      <div class="card-header">
        <div class="d-flex align-items-center mb-1 flex-wrap">
          <h5 class="mb-0 me-1">{{ $totalRoles ?? 0 }}</h5>
          <p class="mb-0 text-primary">Roles</p>
        </div>
        <span class="d-block card-subtitle text-truncate">Manajemen Akses</span>
      </div>
      <div class="card-body">
        <div class="d-flex align-items-center gap-2 mt-0 mt-sm-2">
          <i class="icon-base ri ri-shield-user-line icon-32px text-primary"></i>
          <div>
            <small class="d-block text-body-secondary">Aktif</small>
            <span class="fw-semibold">RBAC System</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Total Users --}}
  <div class="col-6 col-md-3 col-xxl-2">
    <div class="card h-100">
      <div class="card-header">
        <div class="d-flex align-items-center mb-1 flex-wrap">
          <h5 class="mb-0 me-1">{{ $totalUsers ?? 0 }}</h5>
          <p class="mb-0 text-info">Users</p>
        </div>
        <span class="d-block card-subtitle text-truncate">Semua Pengguna</span>
      </div>
      <div class="card-body">
        <div class="d-flex align-items-center gap-2 mt-0 mt-sm-2">
          <i class="icon-base ri ri-user-3-line icon-32px text-info"></i>
          <div>
            <small class="d-block text-body-secondary">Terdaftar</small>
            <span class="fw-semibold">Sistem Aktif</span>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<div class="row g-3 g-xl-6">

  {{-- Logbook per Bulan Chart --}}
  <div class="col-lg-4 col-md-6 order-1 order-lg-0">
    <div class="card h-100">
      <div class="card-header pb-1">
        <div class="d-flex justify-content-between">
          <h5 class="mb-1">Logbook Per Bulan</h5>
          <div class="dropdown">
            <button class="btn btn-text-secondary rounded-pill text-body-secondary border-0 p-1" type="button"
              data-bs-toggle="dropdown">
              <i class="icon-base ri ri-more-2-line"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
              <a class="dropdown-item" href="javascript:void(0);">7 Bulan Terakhir</a>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div id="logbookPerMonthChart"></div>
        <div class="mt-4 text-center">
          <p class="mb-1 fw-semibold">{{ $logbookThisMonth ?? 0 }} laporan bulan ini</p>
          <small class="text-body-secondary">Total seluruhnya: {{ $totalLogbooks ?? 0 }} logbook</small>
        </div>
      </div>
    </div>
  </div>

  {{-- Distribusi Intern per Divisi --}}
  <div class="col-lg-4 col-md-6">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0 me-2">Intern per Divisi</h5>
      </div>
      <div class="card-body">
        <div id="internDivisionChart"></div>
      </div>
    </div>
  </div>

  {{-- Logbook Terbaru --}}
  <div class="col-lg-4 col-md-6">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0 me-2">Logbook Terbaru</h5>
        @if(auth()->user()->hasAnyRole(['superadmin', 'admin']))
        <a href="{{ route('admin.logbooks.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        @endif
      </div>
      <div class="card-body">
        <ul class="p-0 m-0">
          @forelse($recentLogbooks ?? [] as $logbook)
          <li class="d-flex align-items-center mb-4">
            <div class="avatar flex-shrink-0 me-4">
              <span class="avatar-initial rounded-3 bg-label-primary fw-bold">
                {{ strtoupper(substr($logbook->intern->user->name ?? 'U', 0, 2)) }}
              </span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">{{ $logbook->intern->user->name ?? '-' }}</h6>
                <small class="d-flex align-items-center">
                  <i class="icon-base ri ri-calendar-line icon-16px"></i>
                  <span class="ms-1">{{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('d M Y') }}</span>
                </small>
              </div>
              @if(auth()->user()->hasAnyRole(['superadmin', 'admin']))
              <a href="{{ route('admin.logbooks.show', $logbook->id) }}" class="badge bg-label-primary rounded-pill">
                Detail
              </a>
              @endif
            </div>
          </li>
          @empty
          <li class="text-center text-body-secondary py-4">
            <i class="icon-base ri ri-draft-line icon-32px mb-2"></i>
            <p class="mb-0">Belum ada logbook</p>
          </li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>

  {{-- Daftar Role Aktif --}}
  <div class="col-lg-4 col-md-6">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0 me-2">Roles Aktif</h5>
        @if(auth()->user()->hasAnyRole(['superadmin']))
        <a href="{{ route('roles.index') }}" class="btn btn-sm btn-outline-secondary">Kelola</a>
        @endif
      </div>
      <div class="card-body">
        <ul class="p-0 m-0">
          @forelse($roles ?? [] as $role)
          <li class="d-flex align-items-center mb-4">
            <div class="avatar flex-shrink-0 me-4">
              <div class="avatar-initial bg-label-{{ ['primary','success','warning','info','danger'][$loop->index % 5] }} rounded">
                <i class="icon-base ri ri-shield-user-line icon-24px"></i>
              </div>
            </div>
            <div class="d-flex w-100 align-items-center justify-content-between gap-2">
              <div>
                <h6 class="mb-0">{{ ucfirst($role->name) }}</h6>
                <small>{{ $role->users_count ?? 0 }} pengguna</small>
              </div>
              <span class="badge bg-label-secondary rounded-pill">{{ $role->guard_name }}</span>
            </div>
          </li>
          @empty
          <li class="text-center text-body-secondary py-4">Belum ada role</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>

  {{-- Quick Stats --}}
  <div class="col-lg-8">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="mb-1">Ringkasan Sistem</h5>
        <p class="mb-0 card-subtitle">Intern Management System — Status aktif</p>
      </div>
      <div class="card-body">
        <div class="row gy-4">
          <div class="col-md-6">
            <div class="d-flex align-items-center mb-4">
              <div class="avatar me-4">
                <div class="avatar-initial bg-label-primary rounded">
                  <i class="icon-base ri ri-group-2-line icon-24px"></i>
                </div>
              </div>
              <div>
                <h6 class="mb-1">Total Pengguna</h6>
                <small>{{ $totalUsers ?? 0 }} user terdaftar</small>
              </div>
            </div>
            <div class="d-flex align-items-center mb-4">
              <div class="avatar me-4">
                <div class="avatar-initial bg-label-success rounded">
                  <i class="icon-base ri ri-graduation-cap-line icon-24px"></i>
                </div>
              </div>
              <div>
                <h6 class="mb-1">Anak Magang</h6>
                <small>{{ $totalInterns ?? 0 }} intern aktif</small>
              </div>
            </div>
            <div class="d-flex align-items-center">
              <div class="avatar me-4">
                <div class="avatar-initial bg-label-warning rounded">
                  <i class="icon-base ri ri-draft-line icon-24px"></i>
                </div>
              </div>
              <div>
                <h6 class="mb-1">Total Logbook</h6>
                <small>{{ $totalLogbooks ?? 0 }} laporan tersimpan</small>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="d-flex align-items-center mb-4">
              <div class="avatar me-4">
                <div class="avatar-initial bg-label-info rounded">
                  <i class="icon-base ri ri-lock-2-line icon-24px"></i>
                </div>
              </div>
              <div>
                <h6 class="mb-1">Roles Sistem</h6>
                <small>{{ $totalRoles ?? 0 }} role aktif</small>
              </div>
            </div>
            <div class="d-flex align-items-center mb-4">
              <div class="avatar me-4">
                <div class="avatar-initial bg-label-danger rounded">
                  <i class="icon-base ri ri-key-2-line icon-24px"></i>
                </div>
              </div>
              <div>
                <h6 class="mb-1">Permissions</h6>
                <small>{{ $totalPermissions ?? 0 }} permission terdaftar</small>
              </div>
            </div>
            <div class="d-flex align-items-center">
              <div class="avatar me-4">
                <div class="avatar-initial bg-label-secondary rounded">
                  <i class="icon-base ri ri-calendar-check-line icon-24px"></i>
                </div>
              </div>
              <div>
                <h6 class="mb-1">Logbook Bulan Ini</h6>
                <small>{{ $logbookThisMonth ?? 0 }} laporan baru</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

{{-- Render PHP data sebagai variabel JS (aman dari Blade parser) --}}
<script>
  var _logbooksPerMonth = {!! json_encode($logbooksPerMonth ?? [0,0,0,0,0,0,0]) !!};
  var _divisionCounts   = {!! json_encode($divisionCounts ?? [1]) !!};
  var _divisionLabels   = {!! json_encode($divisionLabels ?? ['Semua']) !!};
</script>

@verbatim
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Logbook per month - Bar chart
  var logbookChartEl = document.getElementById('logbookPerMonthChart');
  if (logbookChartEl && typeof ApexCharts !== 'undefined') {
    new ApexCharts(logbookChartEl, {
      chart: { type: 'bar', height: 120, sparkline: { enabled: true } },
      series: [{ name: 'Logbook', data: _logbooksPerMonth }],
      colors: ['#666cff'],
      plotOptions: { bar: { columnWidth: '60%', borderRadius: 4 } },
      tooltip: { theme: 'dark' },
    }).render();
  }

  // Intern per division - Donut chart
  var divisionChartEl = document.getElementById('internDivisionChart');
  if (divisionChartEl && typeof ApexCharts !== 'undefined') {
    new ApexCharts(divisionChartEl, {
      chart: { type: 'donut', height: 200 },
      series: _divisionCounts.length ? _divisionCounts : [1],
      labels: _divisionLabels.length ? _divisionLabels : ['Semua'],
      colors: ['#666cff', '#28c76f', '#ff4c51', '#ff9f43', '#00cfe8'],
      legend: { position: 'bottom' },
      dataLabels: { enabled: false },
    }).render();
  }
});
</script>
@endverbatim
@endsection
