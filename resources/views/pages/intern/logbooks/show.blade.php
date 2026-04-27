@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Logbook')

@section('page-style')
  @vite('resources/assets/vendor/scss/pages/app-academy-details.scss')
@endsection

@section('content')
<div class="g-3">
  <div class="row g-6">

    {{-- Main Content --}}
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center flex-wrap mb-6 gap-1">
            <div class="me-1">
              <h5 class="mb-0">Detail Logbook Harian</h5>
              <p class="mb-0">Tanggal: <span class="fw-medium text-heading">{{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('l, d F Y') }}</span></p>
            </div>
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-label-info rounded-pill">Logbook</span>
              <a href="{{ route('intern.logbooks.edit', $logbook->id) }}" class="icon-base ri ri-edit-line icon-24px mx-2 text-body" title="Edit"></a>
              <a href="{{ route('intern.logbooks.index') }}" class="icon-base ri ri-arrow-left-line icon-24px text-body" title="Kembali"></a>
            </div>
          </div>

          <div class="card academy-content shadow-none border">
            <div class="card-body">
              <div class="d-flex align-items-center mb-4 p-4 bg-label-info rounded-3">
                <i class="icon-base ri ri-calendar-event-line icon-24px text-info me-3"></i>
                <div>
                  <small class="text-body-secondary d-block">Tanggal Laporan</small>
                  <h6 class="mb-0 fw-semibold">{{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('l, d F Y') }}</h6>
                </div>
              </div>

              <h5>Uraian Aktivitas</h5>
              <p class="mb-0">{{ $logbook->uraian_aktivitas }}</p>
              <hr class="my-6" />

              <h5>Pembelajaran yang Diperoleh</h5>
              <div class="d-flex flex-wrap row-gap-2">
                <div>
                  <p class="mb-2"><i class="icon-base ri ri-check-line icon-20px me-2 text-success"></i>{{ $logbook->pembelajaran_diperoleh }}</p>
                </div>
              </div>

              @if($logbook->kendala_dialami)
              <hr class="my-6" />
              <h5 class="text-danger"><i class="icon-base ri ri-error-warning-line icon-20px me-2"></i>Kendala yang Dialami</h5>
              <div class="alert alert-danger d-flex align-items-start" role="alert">
                <i class="icon-base ri ri-alert-line icon-20px me-3 mt-1 flex-shrink-0"></i>
                <p class="mb-0">{{ $logbook->kendala_dialami }}</p>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">
      <div class="stick-top">
        <div class="accordion accordion-custom-button" id="internLogbookSidebar">
          <div class="accordion-item active mb-0">
            <div class="accordion-header border-bottom-0" id="headingSummary">
              <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#logbookSidebar"
                aria-expanded="true" aria-controls="logbookSidebar">
                <span class="d-flex flex-column">
                  <span class="h5 mb-0">Info Logbook</span>
                  <span class="text-body fw-normal">Ringkasan laporan kamu</span>
                </span>
              </button>
            </div>
            <div id="logbookSidebar" class="accordion-collapse collapse show" data-bs-parent="#internLogbookSidebar">
              <div class="accordion-body py-4">
                <div class="form-check mb-4">
                  <span class="d-flex align-items-center">
                    <i class="icon-base ri ri-calendar-check-line icon-20px me-3 text-info"></i>
                    <span>
                      <small class="text-body-secondary d-block">Tanggal</small>
                      <span class="fw-medium">{{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('d M Y') }}</span>
                    </span>
                  </span>
                </div>
                <div class="form-check mb-4">
                  <span class="d-flex align-items-center">
                    <i class="icon-base ri ri-time-line icon-20px me-3 text-info"></i>
                    <span>
                      <small class="text-body-secondary d-block">Dibuat</small>
                      <span class="fw-medium">{{ $logbook->created_at->diffForHumans() }}</span>
                    </span>
                  </span>
                </div>
                <div class="form-check mb-4">
                  <span class="d-flex align-items-center">
                    @if($logbook->kendala_dialami)
                    <i class="icon-base ri ri-error-warning-line icon-20px me-3 text-danger"></i>
                    <span>
                      <small class="text-body-secondary d-block">Status</small>
                      <span class="fw-medium text-danger">Ada Kendala</span>
                    </span>
                    @else
                    <i class="icon-base ri ri-check-double-line icon-20px me-3 text-success"></i>
                    <span>
                      <small class="text-body-secondary d-block">Status</small>
                      <span class="fw-medium text-success">Berjalan Lancar</span>
                    </span>
                    @endif
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex flex-column gap-3 mt-4">
          <a href="{{ route('intern.logbooks.edit', $logbook->id) }}" class="btn btn-primary d-flex align-items-center justify-content-center">
            <i class="icon-base ri ri-edit-line icon-16px me-2"></i> Edit Laporan Ini
          </a>
          <a href="{{ route('intern.logbooks.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center">
            <i class="icon-base ri ri-arrow-left-line icon-16px me-2"></i> Kembali ke Daftar
          </a>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
