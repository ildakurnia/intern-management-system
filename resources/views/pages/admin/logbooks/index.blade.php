@extends('layouts/contentNavbarLayout')

@section('title', 'Monitoring Logbook')

@section('page-style')
  @vite('resources/assets/vendor/scss/pages/app-academy.scss')
@endsection

@section('content')
<div class="app-academy">

  {{-- Hero Banner --}}
  <div class="card p-0 mb-6">
    <div class="card-body d-flex flex-column flex-md-row justify-content-between p-0 pt-6">
      <div class="app-academy-md-25 card-body py-0 pt-6 ps-12">
        <img src="{{ asset('assets/img/illustrations/bulb-light.png') }}"
          class="img-fluid app-academy-img-height scaleX-n1-rtl" alt="Bulb in hand"
          data-app-light-img="illustrations/bulb-light.png" data-app-dark-img="illustrations/bulb-dark.png"
          height="90" />
      </div>
      <div class="app-academy-md-50 card-body d-flex align-items-md-center flex-column text-md-center mb-6 py-6">
        <span class="card-title mb-4 lh-lg px-md-12 h4 text-heading">
          Monitoring Laporan Harian Intern<br />
          <span class="text-primary text-nowrap">Pantau progres mereka.</span>
        </span>
        <p class="mb-4 px-0 px-md-2">
          Semua aktivitas, pembelajaran, dan kendala anak magang terekam rapi di sini.<br />
          Lihat detail setiap laporan dengan mudah.
        </p>
      </div>
      <div class="app-academy-md-25 d-flex align-items-end justify-content-end">
        <img src="{{ asset('assets/img/illustrations/pencil-rocket.png') }}" alt="pencil rocket" height="180"
          class="scaleX-n1-rtl" />
      </div>
    </div>
  </div>

  {{-- Logbook Cards --}}
  <div class="card mb-6">
    <div class="card-header d-flex flex-wrap justify-content-between gap-4">
      <div class="card-title mb-0 me-1">
        <h5 class="mb-0">Daftar Logbook Intern</h5>
        <p class="mb-0 text-body">Total {{ $logbooks->total() }} laporan ditemukan</p>
      </div>
    </div>
    <div class="card-body mt-1">
      <div class="row gy-6 mb-6">
        @forelse ($logbooks as $logbook)
          <div class="col-sm-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
              <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-4">
                  <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-md">
                      <div class="avatar-initial bg-label-primary rounded-circle">
                        {{ strtoupper(substr($logbook->intern->user->name, 0, 2)) }}
                      </div>
                    </div>
                    <div>
                      @can('admin.logbooks.show')
                      <a href="{{ route('admin.logbooks.show', $logbook->id) }}" class="h6 mb-0 text-heading text-truncate d-block" style="max-width: 150px;">{{ $logbook->intern->user->name }}</a>
                      @else
                      <h6 class="mb-0 text-heading text-truncate d-block" style="max-width: 150px;">{{ $logbook->intern->user->name }}</h6>
                      @endcan
                      <small class="text-body-secondary"><i class="icon-base ri ri-building-line align-middle me-1"></i>{{ $logbook->intern->division->name ?? 'Divisi -' }}</small>
                    </div>
                  </div>
                  <span class="badge bg-label-primary rounded-pill">Logbook</span>
                </div>

                <div class="mb-4">
                  <p class="text-body-secondary text-sm mb-2">
                    <i class="icon-base ri ri-calendar-event-line align-middle me-1"></i> {{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('d M Y') }}
                  </p>
                  <div class="bg-lighter p-3 rounded text-body" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; font-size: 0.9rem; min-height: 70px;">
                    {{ $logbook->uraian_aktivitas }}
                  </div>
                </div>

                <div class="mt-auto">
                  @can('admin.logbooks.show')
                  <a class="btn btn-outline-primary w-100" href="{{ route('admin.logbooks.show', $logbook->id) }}">
                    Lihat Detail
                  </a>
                  @endcan
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="col-12">
            <div class="text-center p-6 bg-label-secondary rounded-3">
              <i class="icon-base ri ri-draft-line icon-48px mb-3 text-secondary"></i>
              <h5>Belum ada laporan</h5>
              <p class="text-body mb-0">Anak magang belum mengunggah logbook mereka.</p>
            </div>
          </div>
        @endforelse
      </div>

      {{-- Pagination --}}
      @if($logbooks->hasPages())
      <nav aria-label="Page navigation" class="d-flex align-items-center justify-content-center">
        {{ $logbooks->links('pagination::bootstrap-5') }}
      </nav>
      @endif
    </div>
  </div>

</div>
@endsection
