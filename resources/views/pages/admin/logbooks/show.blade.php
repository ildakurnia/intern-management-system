@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Logbook Intern')

@section('page-style')
  @vite('resources/assets/vendor/scss/pages/app-academy-details.scss')
  <style>
    .logbook-detail-card {
      overflow: hidden;
    }

    .logbook-detail-text {
      white-space: pre-wrap;
      word-break: break-word;
      overflow-wrap: anywhere;
      line-height: 1.7;
    }

    .logbook-detail-box {
      border-radius: 0.9rem;
      background: rgba(var(--bs-primary-rgb), 0.08);
    }

    .logbook-detail-box p,
    .logbook-detail-box h6,
    .logbook-detail-box small {
      margin-bottom: 0;
    }

    .logbook-detail-alert {
      overflow: hidden;
    }

    .logbook-detail-alert .logbook-detail-text {
      line-height: 1.65;
    }

    @media (max-width: 991.98px) {
      .stick-top {
        position: static !important;
        top: auto !important;
      }
    }

    @media (max-width: 767.98px) {
      .academy-content .card-body,
      .card > .card-body {
        padding: 1rem;
      }

      .academy-content p,
      .accordion-body p {
        word-break: break-word;
      }

      .logbook-detail-box {
        padding: 0.9rem !important;
      }
    }
  </style>
@endsection

@section('content')
<div class="g-3">
  <div class="row g-6">

    {{-- Main Content --}}
    <div class="col-lg-8">
      <div class="card logbook-detail-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center flex-wrap mb-6 gap-1">
            <div class="me-1">
              <h5 class="mb-0">Laporan Harian Intern</h5>
              <p class="mb-0">Oleh: <span class="fw-medium text-heading">{{ $logbook->intern->user->name }}</span></p>
            </div>
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-label-primary rounded-pill">{{ $logbook->intern->division->name ?? 'Umum' }}</span>
              <a href="{{ route('admin.logbooks.index') }}" class="icon-base ri ri-arrow-left-line icon-24px mx-4 text-body"></a>
            </div>
          </div>

          {{-- Content Card --}}
          <div class="card academy-content shadow-none border logbook-detail-card">
            <div class="card-body">
              <div class="d-flex align-items-center mb-4 p-4 logbook-detail-box">
                <i class="icon-base ri ri-calendar-event-line icon-24px text-primary me-3"></i>
                <div>
                  <small class="text-body-secondary d-block">Tanggal Laporan</small>
                  <h6 class="mb-0 fw-semibold">{{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('l, d F Y') }}</h6>
                </div>
              </div>

              <h5>Uraian Aktivitas</h5>
              <p class="mb-0 logbook-detail-text">{{ $logbook->uraian_aktivitas }}</p>
              <hr class="my-6" />

              <h5>Pembelajaran yang Diperoleh</h5>
              <div class="d-flex flex-wrap row-gap-2">
                <div>
                  <p class="mb-2 logbook-detail-text"><i class="icon-base ri ri-check-line icon-20px me-2 text-success"></i>{{ $logbook->pembelajaran_diperoleh }}</p>
                </div>
              </div>

              @if($logbook->kendala_dialami)
              <hr class="my-6" />
              <h5 class="text-danger"><i class="icon-base ri ri-error-warning-line icon-20px me-2"></i>Kendala yang Dialami</h5>
              <div class="alert alert-danger d-flex align-items-start logbook-detail-alert" role="alert">
                <i class="icon-base ri ri-alert-line icon-20px me-3 mt-1 flex-shrink-0"></i>
                <p class="mb-0 logbook-detail-text">{{ $logbook->kendala_dialami }}</p>
              </div>
              @endif

              <hr class="my-6" />
              <h5>Anak Magang</h5>
              <div class="d-flex justify-content-start align-items-center user-name">
                <div class="avatar-wrapper">
                  <div class="avatar me-4">
                    <span class="avatar-initial rounded-circle bg-label-info fw-bold">
                      {{ strtoupper(substr($logbook->intern->user->name, 0, 2)) }}
                    </span>
                  </div>
                </div>
                <div class="d-flex flex-column">
                  <h6 class="mb-1">{{ $logbook->intern->user->name }}</h6>
                  <small>{{ $logbook->intern->division->name ?? '-' }}</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Sidebar Summary --}}
    <div class="col-lg-4">
      <div class="stick-top">
        <div class="accordion accordion-custom-button" id="logbookSummary">
          <div class="accordion-item active mb-0">
            <div class="accordion-header border-bottom-0" id="headingInfo">
              <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#logbookInfo"
                aria-expanded="true" aria-controls="logbookInfo">
                <span class="d-flex flex-column">
                  <span class="h5 mb-0">Ringkasan Logbook</span>
                  <span class="text-body fw-normal">Data laporan intern</span>
                </span>
              </button>
            </div>
            <div id="logbookInfo" class="accordion-collapse collapse show" data-bs-parent="#logbookSummary">
              <div class="accordion-body py-4">
                <div class="mb-4 d-flex align-items-center">
                  <i class="icon-base ri ri-user-3-line icon-20px me-3 text-primary"></i>
                  <div>
                    <small class="text-body-secondary">Nama Intern</small>
                    <p class="mb-0 fw-medium">{{ $logbook->intern->user->name }}</p>
                  </div>
                </div>
                <div class="mb-4 d-flex align-items-center">
                  <i class="icon-base ri ri-building-line icon-20px me-3 text-primary"></i>
                  <div>
                    <small class="text-body-secondary">Divisi</small>
                    <p class="mb-0 fw-medium">{{ $logbook->intern->division->name ?? '-' }}</p>
                  </div>
                </div>
                <div class="mb-4 d-flex align-items-center">
                  <i class="icon-base ri ri-calendar-check-line icon-20px me-3 text-primary"></i>
                  <div>
                    <small class="text-body-secondary">Tanggal Laporan</small>
                    <p class="mb-0 fw-medium">{{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('d M Y') }}</p>
                  </div>
                </div>
                <div class="mb-4 d-flex align-items-center">
                  <i class="icon-base ri ri-time-line icon-20px me-3 text-primary"></i>
                  <div>
                    <small class="text-body-secondary">Dibuat</small>
                    <p class="mb-0 fw-medium">{{ $logbook->created_at->diffForHumans() }}</p>
                  </div>
                </div>
                @if($logbook->kendala_dialami)
                <div class="mb-4 d-flex align-items-center">
                  <i class="icon-base ri ri-error-warning-line icon-20px me-3 text-danger"></i>
                  <div>
                    <small class="text-body-secondary">Status Kendala</small>
                    <p class="mb-0 fw-medium text-danger">Ada Kendala</p>
                  </div>
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>

        <div class="mt-4">
          <a href="{{ route('admin.logbooks.index') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center">
            <i class="icon-base ri ri-arrow-left-line icon-16px me-2"></i> Kembali ke Daftar
          </a>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
