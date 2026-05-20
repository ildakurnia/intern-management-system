@extends('layouts/contentNavbarLayout')

@section('title', 'Masa Magang Selesai')

@php
  $internName = $intern?->name ?? auth()->user()->name;
  $startDate = $intern?->start_date;
  $endDate = $intern?->end_date;
@endphp

@section('page-style')
  <style>
    .intern-period-page {
      display: grid;
      gap: 1rem;
    }

    .intern-period-card {
      border: 1px solid rgba(148, 163, 184, 0.14);
      border-radius: 1.7rem;
      background: rgba(255, 255, 255, 0.97);
      box-shadow: 0 18px 44px rgba(15, 23, 42, 0.06);
      overflow: hidden;
    }

    .intern-period-hero {
      padding: 1.9rem;
      background:
        radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 26%),
        linear-gradient(135deg, #0f172a 0%, #334155 48%, #4f46e5 100%);
      color: #fff;
    }

    .intern-period-eyebrow {
      margin: 0 0 0.55rem;
      color: rgba(255, 255, 255, 0.76);
      font-size: 0.8rem;
      font-weight: 800;
      letter-spacing: 0.14em;
      text-transform: uppercase;
    }

    .intern-period-title {
      margin: 0;
      font-size: clamp(1.9rem, 3vw, 2.5rem);
      font-weight: 800;
      letter-spacing: -0.04em;
      line-height: 1.05;
    }

    .intern-period-subtitle {
      max-width: 42rem;
      margin: 0.95rem 0 0;
      color: rgba(255, 255, 255, 0.86);
      font-size: 1rem;
      line-height: 1.7;
    }

    .intern-period-body {
      padding: 1.5rem 1.6rem 1.7rem;
    }

    .intern-period-alert {
      display: flex;
      align-items: flex-start;
      gap: 0.85rem;
      padding: 1rem 1.1rem;
      border-radius: 1.05rem;
      background: rgba(99, 102, 241, 0.08);
      border: 1px solid rgba(99, 102, 241, 0.16);
      color: #3730a3;
    }

    .intern-period-alert i {
      font-size: 1.2rem;
      margin-top: 0.1rem;
    }

    .intern-period-alert strong {
      display: block;
      margin-bottom: 0.25rem;
      font-size: 0.98rem;
      font-weight: 800;
    }

    .intern-period-alert p {
      margin: 0;
      line-height: 1.6;
    }

    .intern-period-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 1rem;
      margin-top: 1.15rem;
    }

    .intern-period-stat {
      padding: 1.15rem;
      border: 1px solid rgba(148, 163, 184, 0.14);
      border-radius: 1.15rem;
      background: rgba(248, 250, 252, 0.92);
    }

    .intern-period-stat small {
      display: block;
      margin-bottom: 0.35rem;
      color: #94a3b8;
      font-size: 0.78rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.08em;
    }

    .intern-period-stat strong {
      color: #1e293b;
      font-size: 1rem;
      font-weight: 800;
    }

    .intern-period-help {
      margin-top: 1.15rem;
      color: #64748b;
      font-size: 0.95rem;
      line-height: 1.7;
    }

    .intern-period-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.8rem;
      margin-top: 1.25rem;
    }

    .intern-period-actions .btn {
      min-width: 11rem;
    }

    @media (max-width: 991.98px) {
      .intern-period-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
@endsection

@section('content')
  <div class="intern-period-page">
    @if (session('status'))
      <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <section class="intern-period-card">
      <div class="intern-period-hero">
        <p class="intern-period-eyebrow">Akses Intern</p>
        <h1 class="intern-period-title">Masa Magang Telah Selesai</h1>
        <p class="intern-period-subtitle">
          Halo {{ $internName }}, periode magang Anda sudah berakhir. Data akun dan histori aktivitas tetap tersimpan di sistem, tetapi akses ke fitur intern sudah ditutup.
        </p>
      </div>

      <div class="intern-period-body">
        <div class="intern-period-alert">
          <i class="ri ri-time-line"></i>
          <div>
            <strong>Akses intern sudah dinonaktifkan</strong>
            <p>Anda tidak bisa lagi mengisi absensi atau logbook untuk periode yang sudah lewat. Silakan hubungi admin jika ada data yang perlu dikonfirmasi.</p>
          </div>
        </div>

        <div class="intern-period-grid">
          <div class="intern-period-stat">
            <small>Status Akun</small>
            <strong>{{ $intern?->status_label ?? 'Selesai' }}</strong>
          </div>
          <div class="intern-period-stat">
            <small>Mulai Magang</small>
            <strong>{{ $startDate ? $startDate->translatedFormat('d M Y') : '-' }}</strong>
          </div>
          <div class="intern-period-stat">
            <small>Selesai Magang</small>
            <strong>{{ $endDate ? $endDate->translatedFormat('d M Y') : '-' }}</strong>
          </div>
        </div>

        <p class="intern-period-help">
          Jika Anda perlu mendapatkan rekap logbook, absensi, atau surat selesai magang, silakan koordinasi dengan admin atau mentor pembimbing.
        </p>

        <div class="intern-period-actions">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
              <i class="ri ri-logout-box-r-line me-1"></i> Logout
            </button>
          </form>
        </div>
      </div>
    </section>
  </div>
@endsection
