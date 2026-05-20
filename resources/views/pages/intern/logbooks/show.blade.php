@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Logbook')

@section('page-style')
  @vite('resources/assets/vendor/scss/pages/app-academy-details.scss')
  <style>
    .intern-logbook-lock-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      padding: 0.5rem 0.8rem;
      border-radius: 999px;
      background: rgba(15, 23, 42, 0.06);
      color: #334155;
      font-size: 0.8rem;
      font-weight: 700;
    }

    .intern-logbook-back-btn {
      background: linear-gradient(135deg, #5b6ee6, #465ad6);
      border: 1px solid rgba(71, 85, 221, 0.22);
      color: #fff;
      box-shadow: 0 10px 22px rgba(58, 69, 170, 0.16);
    }

    .intern-logbook-back-btn:hover,
    .intern-logbook-back-btn:focus {
      color: #fff;
      background: linear-gradient(135deg, #6678ea, #4f63dc);
      border-color: rgba(71, 85, 221, 0.3);
      box-shadow: 0 12px 24px rgba(58, 69, 170, 0.2);
    }

    html[data-bs-theme="dark"] .intern-logbook-back-btn {
      background: linear-gradient(135deg, #5569de, #4357cc);
      border-color: rgba(255, 255, 255, 0.08);
      box-shadow: 0 10px 22px rgba(0, 0, 0, 0.18);
    }

    html[data-bs-theme="dark"] .intern-logbook-back-btn:hover,
    html[data-bs-theme="dark"] .intern-logbook-back-btn:focus {
      background: linear-gradient(135deg, #6275e5, #4d61d6);
      border-color: rgba(255, 255, 255, 0.12);
    }

    @media (max-width: 767.98px) {
      .intern-logbook-lock-badge {
        width: 100%;
        justify-content: center;
      }

      .academy-content .card-body,
      .card > .card-body {
        padding: 1rem;
      }

      .academy-content p {
        word-break: break-word;
      }
    }
  </style>
@endsection

@section('content')
<div class="g-3">
  @if (session('status'))
    <div class="alert alert-info alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
      {{ session('status') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center flex-wrap mb-6 gap-2">
        <div class="me-1">
          <h5 class="mb-0">Detail Logbook Harian</h5>
        </div>
        <div class="d-flex align-items-center gap-2">
          <span class="badge bg-label-info rounded-pill">Logbook</span>
          <span class="intern-logbook-lock-badge">
            <i class="ri ri-lock-2-line"></i>
            Terkunci
          </span>
        </div>
      </div>

      <div class="row g-4 mb-6">
        <div class="col-md-4">
          <div class="border rounded-4 p-4 h-100 bg-label-info">
            <div class="d-flex align-items-center">
              <i class="icon-base ri ri-calendar-check-line icon-20px me-3 text-info"></i>
              <div>
                <small class="text-body-secondary d-block">Tanggal</small>
                <span class="fw-medium">{{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('d M Y') }}</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="border rounded-4 p-4 h-100">
            <div class="d-flex align-items-center">
              <i class="icon-base ri ri-time-line icon-20px me-3 text-info"></i>
              <div>
                <small class="text-body-secondary d-block">Dibuat</small>
                <span class="fw-medium">{{ $logbook->created_at->diffForHumans() }}</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="border rounded-4 p-4 h-100">
            <div class="d-flex align-items-center">
              @if($logbook->kendala_dialami)
                <i class="icon-base ri ri-error-warning-line icon-20px me-3 text-danger"></i>
                <div>
                  <small class="text-body-secondary d-block">Status</small>
                  <span class="fw-medium text-danger">Ada Kendala</span>
                </div>
              @else
                <i class="icon-base ri ri-check-double-line icon-20px me-3 text-success"></i>
                <div>
                  <small class="text-body-secondary d-block">Status</small>
                  <span class="fw-medium text-success">Berjalan Lancar</span>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="card academy-content shadow-none border">
        <div class="card-body">
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

      <div class="d-flex flex-column gap-3 mt-4">
        <div class="btn btn-outline-secondary d-flex align-items-center justify-content-center disabled">
          <i class="icon-base ri ri-lock-2-line icon-16px me-2"></i> Logbook Sudah Terkunci
        </div>
        <a href="{{ route('intern.logbooks.index') }}" class="btn intern-logbook-back-btn d-flex align-items-center justify-content-center">
          <i class="icon-base ri ri-arrow-left-line icon-16px me-2"></i> Kembali ke Daftar
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
