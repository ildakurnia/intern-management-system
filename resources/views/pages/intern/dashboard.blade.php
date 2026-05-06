@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard Intern')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/apex-charts/apex-charts.scss',
])
<style>
  .onboarding-timeline .timeline-item {
    padding-left: 1rem;
  }
  .timeline-dot {
    left: 0;
    transition: all 0.3s ease;
  }
  .timeline-line {
    top: 0;
    left: 14px !important;
    z-index: 0;
  }
  .timeline-item:hover .timeline-dot {
    transform: scale(1.1);
  }
</style>
@endsection

@section('content')
<div class="row g-6 mb-6">

  {{-- Welcome Card --}}
  <div class="col-xxl-8 col-lg-7">
    <div class="card h-100 shadow-sm border-0 bg-label-primary">
      <div class="card-body d-flex justify-content-between flex-wrap-reverse align-items-center">
        <div class="me-3 py-3">
          <h4 class="mb-1 text-primary">Selamat datang kembali, <span class="fw-bold">{{ auth()->user()->name }}!</span> 👋</h4>
          <p class="mb-4">Semangat menjalani aktivitas magang hari ini di Persero Batam.</p>
          <div class="d-flex align-items-center gap-3">
            <a href="{{ route('intern.logbooks.create') }}" class="btn btn-primary shadow">
              <i class="ri-add-line me-1"></i> Buat Logbook
            </a>
            @if(!$hasCompletedProfile)
            <a href="{{ route('intern.profile.edit') }}" class="btn btn-outline-primary">
              Lengkapi Profil
            </a>
            @endif
          </div>
        </div>
        <div class="p-3 text-center">
          <img src="{{ asset('assets/img/illustrations/boy-with-laptop-light.png') }}" height="150" alt="Illustration" />
        </div>
      </div>
    </div>
  </div>

  {{-- Profile Incomplete Alert --}}
  @if(!$hasCompletedProfile || !$hasCompletedDocuments)
  <div class="col-12 mt-4">
    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-0" role="alert">
      <div class="avatar avatar-sm me-3">
        <span class="avatar-initial bg-warning rounded-circle"><i class="ri ri-error-warning-line"></i></span>
      </div>
      <div class="d-flex flex-column flex-grow-1">
        <h6 class="alert-heading mb-1 fw-bold">Profil Belum Lengkap ({{ $profileCompleteness }}%)</h6>
        <span>Harap lengkapi data diri dan unggah berkas wajib agar proses magang dapat diverifikasi sepenuhnya.</span>
      </div>
      <a href="{{ route('intern.profile.edit') }}" class="btn btn-warning shadow-sm ms-auto">Lengkapi Sekarang</a>
    </div>
  </div>
  @elseif($intern->registration_status !== 'approved')
  <div class="col-12 mt-4">
    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-0" role="alert">
      <div class="avatar avatar-sm me-3">
        <span class="avatar-initial bg-info rounded-circle"><i class="ri ri-time-line"></i></span>
      </div>
      <div class="d-flex flex-column flex-grow-1">
        <h6 class="alert-heading mb-1 fw-bold">Menunggu Verifikasi Admin</h6>
        <span>Anda telah melengkapi semua profil & berkas. Silakan tunggu admin memverifikasi dan menyetujui akun Anda.</span>
      </div>
    </div>
  </div>
  @endif

  {{-- Onboarding Flow & Progress --}}
  <div class="col-xxl-4 col-lg-5">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-header pb-2 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Profil & Onboarding</h5>
        <span class="badge bg-label-primary rounded-pill">{{ $profileCompleteness }}%</span>
      </div>
      <div class="card-body">
        {{-- Progress Bar --}}
        <div class="mb-5">
          <div class="d-flex justify-content-between mb-1">
            <small class="fw-medium text-heading">Kelengkapan Profil</small>
            <small class="text-body-secondary">{{ $profileCompleteness }}%</small>
          </div>
          <div class="progress" style="height: 10px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ $profileCompleteness == 100 ? 'success' : 'primary' }}" 
              role="progressbar" style="width: {{ $profileCompleteness }}%" 
              aria-valuenow="{{ $profileCompleteness }}" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>

        {{-- Vertical Onboarding Flow (Timeline) --}}
        <div class="onboarding-timeline ps-3">
          @foreach($onboardingSteps as $step)
          <div class="timeline-item position-relative pb-4">
            {{-- Vertical Line --}}
            @if(!$loop->last)
            <div class="timeline-line position-absolute start-0 h-100 border-start border-2 border-dashed {{ $step['status'] == 'completed' ? 'border-success' : 'border-light' }}" style="margin-left: -15px; top: 10px;"></div>
            @endif
            
            {{-- Dot/Check --}}
            <div class="timeline-dot position-absolute start-0 rounded-circle d-flex align-items-center justify-content-center bg-{{ $step['status'] == 'completed' ? 'success' : 'lighter' }} text-white shadow-sm" 
                 style="width: 28px; height: 28px; margin-left: -29px; z-index: 1;">
              @if($step['status'] == 'completed')
                <i class="ri-check-line icon-14px"></i>
              @else
                <div class="bg-body-secondary rounded-circle" style="width: 8px; height: 8px;"></div>
              @endif
            </div>

            <div class="timeline-content ms-3">
              <h6 class="mb-0 small fw-bold {{ $step['status'] == 'completed' ? 'text-success' : 'text-heading' }}">{{ $step['title'] }}</h6>
              <small class="text-body-secondary d-block">{{ $step['desc'] }}</small>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  {{-- Statistics Cards --}}
  <div class="col-md-4">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-body">
        <div class="avatar mb-4">
          <div class="avatar-initial bg-label-info rounded">
            <i class="ri-draft-line icon-24px"></i>
          </div>
        </div>
        <h5 class="mb-1">{{ $totalLogbooks }}</h5>
        <p class="text-body-secondary mb-0">Total Logbook</p>
        <small class="text-info">{{ $logbookThisMonth }} laporan bulan ini</small>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-body">
        <div class="avatar mb-3">
          <div class="avatar-initial bg-label-success rounded">
            <i class="ri ri-calendar-line icon-24px"></i>
          </div>
        </div>
        <h6 class="mb-1 text-nowrap small fw-bold">{{ $internPeriod['start'] }} - {{ $internPeriod['end'] }}</h6>
        <p class="text-body-secondary mb-3 small">Periode Magang</p>
        
        <div class="progress mb-1" style="height: 6px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $internPeriod['percentage'] }}%" aria-valuenow="{{ $internPeriod['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <div class="d-flex justify-content-between small">
            <small class="text-body-secondary">{{ $internPeriod['daysPassed'] }} hari</small>
            <small class="text-body-secondary">{{ $internPeriod['percentage'] }}%</small>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-body">
        <div class="avatar mb-4">
          <div class="avatar-initial bg-label-warning rounded">
            <i class="ri-community-line icon-24px"></i>
          </div>
        </div>
        <h5 class="mb-1 text-nowrap">{{ $intern->division->name ?? 'Belum Ditentukan' }}</h5>
        <p class="text-body-secondary mb-0">Divisi Penempatan</p>
        <small class="text-warning">Kantor Pusat</small>
      </div>
    </div>
  </div>

</div>

<div class="row g-6">
  {{-- Chart --}}
  <div class="col-lg-8">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0">Aktivitas Logbook</h5>
        <small class="text-body-secondary">7 Bulan Terakhir</small>
      </div>
      <div class="card-body">
        <div id="logbookActivityChart"></div>
      </div>
    </div>
  </div>

  {{-- Recent Logbooks --}}
  <div class="col-lg-4">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-header d-flex align-items-center justify-content-between pb-2">
        <h5 class="card-title mb-0">Logbook Terbaru</h5>
        <a href="{{ route('intern.logbooks.index') }}" class="btn btn-sm btn-text-primary p-0">Lihat Semua</a>
      </div>
      <div class="card-body">
        <ul class="p-0 m-0">
          @forelse($recentLogbooks as $log)
          <li class="d-flex align-items-start mb-4">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-lighter text-primary">
                <i class="ri-file-text-line"></i>
              </span>
            </div>
            <div class="d-flex w-100 flex-column">
              <div class="d-flex justify-content-between mb-1">
                <h6 class="mb-0 small">{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d M Y') }}</h6>
                <small class="text-body-secondary">{{ $log->created_at->diffForHumans() }}</small>
              </div>
              <p class="mb-0 small text-truncate" style="max-width: 200px;">{{ $log->kegiatan }}</p>
            </div>
          </li>
          @empty
          <li class="text-center py-5 text-body-secondary">
            <i class="ri-draft-line icon-32px mb-2"></i>
            <p>Belum ada aktivitas</p>
          </li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
  var _logbooksPerMonth = {!! json_encode($logbooksPerMonth ?? [0,0,0,0,0,0,0]) !!};
</script>

@verbatim
<script>
document.addEventListener('DOMContentLoaded', function () {
  var chartEl = document.getElementById('logbookActivityChart');
  if (chartEl && typeof ApexCharts !== 'undefined') {
    new ApexCharts(chartEl, {
      chart: { 
        type: 'area', 
        height: 300,
        toolbar: { show: false },
        sparkline: { enabled: false }
      },
      dataLabels: { enabled: false },
      stroke: { curve: 'smooth', width: 3 },
      series: [{ name: 'Logbook', data: _logbooksPerMonth }],
      colors: ['#666cff'],
      fill: {
        type: 'gradient',
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.5,
          opacityTo: 0.1,
          stops: [0, 90, 100]
        }
      },
      xaxis: {
        labels: { show: false },
        axisBorder: { show: false },
        axisTicks: { show: false }
      },
      yaxis: { show: false },
      grid: { show: false }
    }).render();
  }
});
</script>
@endverbatim

@endsection
