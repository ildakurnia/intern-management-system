@extends('layouts/contentNavbarLayout')

@section('title', 'Menunggu Approval Admin')

@php
  $internName = $intern?->name ?? auth()->user()->name;
  $registeredAt = $intern?->registered_at;
@endphp

@section('page-style')
  <style>
    .intern-approval-page {
      display: grid;
      gap: 1rem;
    }

    .intern-approval-card {
      border: 1px solid rgba(148, 163, 184, 0.14);
      border-radius: 1.7rem;
      background: rgba(255, 255, 255, 0.97);
      box-shadow: 0 18px 44px rgba(15, 23, 42, 0.06);
      overflow: hidden;
    }

    .intern-approval-hero {
      padding: 1.9rem;
      background:
        radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), transparent 26%),
        linear-gradient(135deg, #1d4ed8 0%, #2563eb 48%, #4f46e5 100%);
      color: #fff;
    }

    .intern-approval-eyebrow {
      margin: 0 0 0.55rem;
      color: rgba(255, 255, 255, 0.76);
      font-size: 0.8rem;
      font-weight: 800;
      letter-spacing: 0.14em;
      text-transform: uppercase;
    }

    .intern-approval-title {
      margin: 0;
      font-size: clamp(1.9rem, 3vw, 2.5rem);
      font-weight: 800;
      letter-spacing: -0.04em;
      line-height: 1.05;
    }

    .intern-approval-subtitle {
      max-width: 40rem;
      margin: 0.95rem 0 0;
      color: rgba(255, 255, 255, 0.86);
      font-size: 1rem;
      line-height: 1.7;
    }

    .intern-approval-body {
      padding: 1.5rem 1.6rem 1.7rem;
    }

    .intern-approval-alert {
      display: flex;
      align-items: flex-start;
      gap: 0.85rem;
      padding: 1rem 1.1rem;
      border-radius: 1.05rem;
      background: rgba(250, 204, 21, 0.12);
      border: 1px solid rgba(245, 158, 11, 0.18);
      color: #92400e;
    }

    .intern-approval-alert i {
      font-size: 1.2rem;
      margin-top: 0.1rem;
    }

    .intern-approval-alert strong {
      display: block;
      margin-bottom: 0.25rem;
      font-size: 0.98rem;
      font-weight: 800;
    }

    .intern-approval-alert p {
      margin: 0;
      line-height: 1.6;
    }

    .intern-approval-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 1rem;
      margin-top: 1.15rem;
    }

    .intern-approval-stat {
      padding: 1.15rem;
      border: 1px solid rgba(148, 163, 184, 0.14);
      border-radius: 1.15rem;
      background: rgba(248, 250, 252, 0.92);
    }

    .intern-approval-stat small {
      display: block;
      margin-bottom: 0.35rem;
      color: #94a3b8;
      font-size: 0.78rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.08em;
    }

    .intern-approval-stat strong {
      color: #1e293b;
      font-size: 1rem;
      font-weight: 800;
    }

    .intern-approval-help {
      margin-top: 1.15rem;
      color: #64748b;
      font-size: 0.95rem;
      line-height: 1.7;
    }

    .intern-approval-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.8rem;
      margin-top: 1.25rem;
    }

    .intern-approval-actions .btn {
      min-width: 11rem;
    }

    @media (max-width: 991.98px) {
      .intern-approval-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
@endsection

@section('content')
  <div class="intern-approval-page">
    @if (session('status'))
      <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <section class="intern-approval-card">
      <div class="intern-approval-hero">
        <p class="intern-approval-eyebrow">Akses Intern</p>
        <h1 class="intern-approval-title">Menunggu Approval Admin</h1>
        <p class="intern-approval-subtitle">
          Halo {{ $internName }}, akun Anda sudah terdaftar tetapi akses ke fitur intern belum dibuka. Silakan tunggu persetujuan dari admin sebelum melanjutkan ke dashboard, logbook, absensi, dan berkas.
        </p>
      </div>

      <div class="intern-approval-body">
        <div class="intern-approval-alert">
          <i class="ri ri-error-warning-line"></i>
          <div>
            <strong>Akun masih dalam proses review</strong>
            <p>Admin perlu memverifikasi data registrasi Anda terlebih dahulu. Setelah disetujui, akses fitur intern akan terbuka otomatis.</p>
          </div>
        </div>

        <div class="intern-approval-grid">
          <div class="intern-approval-stat">
            <small>Status Akun</small>
            <strong>{{ ucfirst($intern?->registration_status ?? 'pending') }}</strong>
          </div>
          <div class="intern-approval-stat">
            <small>Akun Login</small>
            <strong>{{ $intern?->user_id ? 'Sudah Terhubung' : 'Belum Terhubung' }}</strong>
          </div>
          <div class="intern-approval-stat">
            <small>Registrasi</small>
            <strong>{{ $registeredAt ? $registeredAt->translatedFormat('d M Y, H:i') : 'Baru dibuat' }}</strong>
          </div>
        </div>

        <p class="intern-approval-help">
          Jika proses approval terasa terlalu lama, Anda bisa menghubungi admin atau pembimbing untuk memastikan data registrasi Anda sudah diterima.
        </p>

        <div class="intern-approval-actions">
          <a href="{{ route('intern.approval.pending') }}" class="btn btn-primary">
            <i class="ri ri-refresh-line me-1"></i> Cek Status Lagi
          </a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">
              <i class="ri ri-logout-box-r-line me-1"></i> Logout
            </button>
          </form>
        </div>
      </div>
    </section>
  </div>
@endsection
