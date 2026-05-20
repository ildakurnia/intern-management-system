@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Logbook Anak Bimbingan')

@section('page-style')
  <style>
  .mentor-logbook-detail-page {
    font-family: var(--bs-body-font-family);
    --mentor-logbook-card-bg: var(--bs-card-bg);
    --mentor-logbook-card-soft: var(--bs-body-bg);
    --mentor-logbook-border: var(--bs-border-color);
      --mentor-logbook-title: var(--bs-heading-color);
      --mentor-logbook-text: var(--bs-body-color);
      --mentor-logbook-soft: var(--bs-secondary-color);
      --mentor-logbook-primary: var(--bs-primary);
      --mentor-logbook-danger: var(--bs-danger);
      --mentor-logbook-shadow: 0 16px 42px rgba(15, 23, 42, 0.06);
    }

    html[data-bs-theme="dark"] .mentor-logbook-detail-page {
      --mentor-logbook-card-bg: rgba(24, 28, 42, 0.88);
      --mentor-logbook-card-soft: rgba(17, 24, 39, 0.94);
      --mentor-logbook-border: rgba(148, 163, 184, 0.14);
      --mentor-logbook-title: #f8fafc;
      --mentor-logbook-text: #e5e7eb;
      --mentor-logbook-soft: #94a3b8;
      --mentor-logbook-primary: #a5b4fc;
      --mentor-logbook-danger: #fda4af;
      --mentor-logbook-shadow: 0 18px 45px rgba(0, 0, 0, 0.24);
    }

    .mentor-logbook-detail-page .card {
      border-color: var(--mentor-logbook-border);
      background: var(--mentor-logbook-card-bg);
      box-shadow: var(--mentor-logbook-shadow);
    }

    .mentor-logbook-detail-page .card-header,
    .mentor-logbook-detail-page .card-footer {
      background: var(--mentor-logbook-card-bg);
      border-color: var(--mentor-logbook-border);
    }

    .mentor-logbook-detail-page .card.shadow-none {
      background: var(--mentor-logbook-card-soft);
    }

    .mentor-logbook-detail-page .text-heading,
    .mentor-logbook-detail-page h5,
    .mentor-logbook-detail-page h6,
    .mentor-logbook-detail-page p,
    .mentor-logbook-detail-page small {
      color: var(--mentor-logbook-text);
    }

    .mentor-logbook-detail-page .text-body-secondary,
    .mentor-logbook-detail-page .text-body,
    .mentor-logbook-detail-page .accordion-body p,
    .mentor-logbook-detail-page .accordion-body small {
      color: var(--mentor-logbook-soft) !important;
    }

    .mentor-logbook-detail-page .accordion-item,
    .mentor-logbook-detail-page .accordion-button {
      border-color: var(--mentor-logbook-border);
      background: var(--mentor-logbook-card-bg);
      color: var(--mentor-logbook-title);
    }

    .mentor-logbook-detail-page .accordion-button:not(.collapsed) {
      background: color-mix(in srgb, var(--mentor-logbook-card-bg) 86%, var(--bs-primary));
      color: var(--mentor-logbook-title);
      box-shadow: none;
    }

    .mentor-logbook-detail-page .btn-outline-secondary {
      color: var(--mentor-logbook-title);
      border-color: var(--mentor-logbook-border);
      background: var(--mentor-logbook-card-bg);
    }

    .mentor-logbook-detail-page .btn-outline-secondary:hover,
    .mentor-logbook-detail-page .btn-outline-secondary:focus {
      color: var(--mentor-logbook-primary);
      border-color: rgba(var(--bs-primary-rgb), 0.22);
      background: rgba(var(--bs-primary-rgb), 0.08);
    }

    .mentor-logbook-detail-page .bg-label-primary {
      background: rgba(var(--bs-primary-rgb), 0.12) !important;
      color: var(--mentor-logbook-primary) !important;
    }

    .mentor-logbook-detail-page .bg-label-danger {
      background: rgba(var(--bs-danger-rgb), 0.12) !important;
      color: var(--mentor-logbook-danger) !important;
    }

    .mentor-logbook-detail-page .badge {
      box-shadow: none;
    }

    .mentor-logbook-detail-page .mentor-logbook-detail-surface {
      background: var(--mentor-logbook-card-soft) !important;
      border-color: var(--mentor-logbook-border) !important;
    }

    .mentor-logbook-detail-page .mentor-logbook-detail-muted {
      color: var(--mentor-logbook-soft) !important;
    }

    .mentor-logbook-detail-page .mentor-logbook-detail-title {
      color: var(--mentor-logbook-title);
    }

    @media (max-width: 991.98px) {
      .stick-top {
        position: static !important;
        top: auto !important;
      }
    }

    @media (max-width: 767.98px) {
      .card.shadow-none > .card-body,
      .card > .card-body {
        padding: 1rem;
      }

      .card p,
      .accordion-body p {
        word-break: break-word;
      }

      .mentor-logbook-detail-page h2 {
        font-size: 1.4rem;
      }

      .mentor-logbook-detail-page .card-header {
        padding-inline: 1rem;
      }
    }
  </style>
@endsection

@section('content')
  <div class="mentor-logbook-detail-page g-3">
    @include('partials.app-breadcrumb', [
      'items' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.mentor')],
        ['label' => 'Logbook Intern', 'url' => route('mentor.logbooks.index')],
        ['label' => 'Detail', 'current' => true],
      ],
    ])

    <div class="row g-6">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-6 gap-1">
              <div class="me-1">
                <h5 class="mb-0">Laporan Harian Intern</h5>
                <p class="mb-0">Oleh: <span class="fw-medium text-heading">{{ $logbook->intern->user->name ?? $logbook->intern->name }}</span></p>
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-label-primary rounded-pill">{{ $logbook->intern->division->name ?? 'Umum' }}</span>
                <a href="{{ route('mentor.logbooks.index') }}" class="icon-base ri ri-arrow-left-line icon-24px mx-4 text-body"></a>
              </div>
            </div>

            <div class="card shadow-none border">
              <div class="card-body">
                <div class="d-flex align-items-center mb-4 p-4 border rounded-3 mentor-logbook-detail-surface">
                  <i class="icon-base ri ri-calendar-event-line icon-24px text-primary me-3"></i>
                  <div>
                    <small class="mentor-logbook-detail-muted d-block">Tanggal Laporan</small>
                    <h6 class="mb-0 fw-semibold">{{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('l, d F Y') }}</h6>
                  </div>
                </div>

                <h5 class="mentor-logbook-detail-title">Uraian Aktivitas</h5>
                <p class="mb-0">{{ $logbook->uraian_aktivitas }}</p>
                <hr class="my-6" />

                <h5 class="mentor-logbook-detail-title">Pembelajaran yang Diperoleh</h5>
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

                <hr class="my-6" />
                <h5>Intern</h5>
                <div class="d-flex justify-content-start align-items-center user-name">
                  <div class="avatar-wrapper">
                    <div class="avatar me-4">
                      <span class="avatar-initial rounded-circle bg-label-info fw-bold">
                        {{ strtoupper(substr($logbook->intern->user->name ?? $logbook->intern->name, 0, 2)) }}
                      </span>
                    </div>
                  </div>
                  <div class="d-flex flex-column">
                    <h6 class="mb-1">{{ $logbook->intern->user->name ?? $logbook->intern->name }}</h6>
                        <small class="mentor-logbook-detail-muted">{{ $logbook->intern->division->name ?? '-' }}</small>
                      </div>
                    </div>
                  </div>
                </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="stick-top">
          <div class="accordion accordion-custom-button" id="logbookSummary">
            <div class="accordion-item active mb-0">
              <div class="accordion-header border-bottom-0" id="headingInfo">
                <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#logbookInfo"
                  aria-expanded="true" aria-controls="logbookInfo">
                  <span class="d-flex flex-column">
                    <span class="h5 mb-0">Ringkasan Logbook</span>
                    <span class="mentor-logbook-detail-muted fw-normal">Data laporan intern bimbingan</span>
                  </span>
                </button>
              </div>
              <div id="logbookInfo" class="accordion-collapse collapse show" data-bs-parent="#logbookSummary">
                <div class="accordion-body py-4">
                  <div class="mb-4 d-flex align-items-center">
                    <i class="icon-base ri ri-user-3-line icon-20px me-3 text-primary"></i>
                    <div>
                      <small class="mentor-logbook-detail-muted">Nama Intern</small>
                      <p class="mb-0 fw-medium">{{ $logbook->intern->user->name ?? $logbook->intern->name }}</p>
                    </div>
                  </div>
                  <div class="mb-4 d-flex align-items-center">
                    <i class="icon-base ri ri-building-line icon-20px me-3 text-primary"></i>
                    <div>
                      <small class="mentor-logbook-detail-muted">Divisi</small>
                      <p class="mb-0 fw-medium">{{ $logbook->intern->division->name ?? '-' }}</p>
                    </div>
                  </div>
                  <div class="mb-4 d-flex align-items-center">
                    <i class="icon-base ri ri-calendar-check-line icon-20px me-3 text-primary"></i>
                    <div>
                      <small class="mentor-logbook-detail-muted">Tanggal Laporan</small>
                      <p class="mb-0 fw-medium">{{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('d M Y') }}</p>
                    </div>
                  </div>
                  <div class="mb-4 d-flex align-items-center">
                    <i class="icon-base ri ri-time-line icon-20px me-3 text-primary"></i>
                    <div>
                      <small class="mentor-logbook-detail-muted">Dibuat</small>
                      <p class="mb-0 fw-medium">{{ $logbook->created_at->diffForHumans() }}</p>
                    </div>
                  </div>
                  @if($logbook->kendala_dialami)
                    <div class="mb-4 d-flex align-items-center">
                      <i class="icon-base ri ri-error-warning-line icon-20px me-3 text-danger"></i>
                      <div>
                        <small class="mentor-logbook-detail-muted">Status Kendala</small>
                        <p class="mb-0 fw-medium text-danger">Ada Kendala</p>
                      </div>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="mt-4">
            <a href="{{ route('mentor.logbooks.index') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center">
              <i class="icon-base ri ri-arrow-left-line icon-16px me-2"></i> Kembali ke Kalender
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
