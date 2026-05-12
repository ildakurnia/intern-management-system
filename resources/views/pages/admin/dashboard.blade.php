@extends('layouts/contentNavbarLayout')

@section('title', 'Admin Dashboard')

@section('page-style')
<style>
  .role-dashboard {
    display: grid;
    gap: 1rem;
  }

  .role-dashboard .row > [class*="col"] {
    min-width: 0;
  }

  .role-dashboard .card {
    border: 1px solid rgba(148, 163, 184, 0.14);
    border-radius: 1.5rem;
    box-shadow: 0 16px 42px rgba(15, 23, 42, 0.06);
  }

  .role-hero {
    overflow: hidden;
    border: 0;
    color: #fff;
    background:
      radial-gradient(circle at top right, rgba(255,255,255,0.18), transparent 26%),
      radial-gradient(circle at bottom left, rgba(125,115,255,0.24), transparent 32%),
      linear-gradient(135deg, #2f27c7 0%, #4f46e5 52%, #625cf2 100%);
  }

  .role-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    padding: .5rem .85rem;
    border-radius: 999px;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.12);
    font-weight: 700;
  }

  .role-hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: .75rem;
    margin-top: 1.5rem;
  }

  .role-hero-actions .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 1rem;
    white-space: normal;
  }

  .role-stat-card {
    padding: 1.25rem 1.35rem;
    height: 100%;
  }

  .role-stat-label {
    color: #8d93ac;
    font-size: .82rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
  }

  .role-stat-value {
    margin-top: .85rem;
    color: #161f39;
    font-size: 2.1rem;
    line-height: 1;
    font-weight: 800;
  }

  .role-stat-note {
    margin-top: .55rem;
    color: #8d93ac;
    font-size: .9rem;
  }

  .role-list-card {
    padding: 1.35rem;
    height: 100%;
  }

  .role-list-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
  }

  .role-list-card h5 {
    margin-bottom: .25rem;
  }

  .role-list-soft {
    color: #8d93ac;
  }

  .role-list {
    display: grid;
    gap: .9rem;
    margin-top: 1rem;
  }

  .role-list-item {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    padding: 1rem 1.05rem;
    border: 1px solid rgba(148, 163, 184, 0.16);
    border-radius: 1rem;
    background: rgba(248, 250, 252, 0.72);
  }

  .role-list-item > div {
    min-width: 0;
  }

  .role-list-item-title {
    color: #1f2937;
    font-weight: 700;
    overflow-wrap: anywhere;
  }

  .role-list-item-meta {
    margin-top: .2rem;
    color: #64748b;
    font-size: .84rem;
    overflow-wrap: anywhere;
  }

  .role-list-item-sub {
    margin-top: .45rem;
    color: #475569;
    font-size: .84rem;
    line-height: 1.55;
    overflow: hidden;
    overflow-wrap: anywhere;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
  }

  @media (max-width: 767.98px) {
    .role-dashboard {
      gap: .8rem;
    }

    .role-dashboard .row {
      --bs-gutter-x: .75rem;
      --bs-gutter-y: .75rem;
    }

    .role-dashboard .card {
      border-radius: 1rem;
      box-shadow: 0 .5rem 1.25rem rgba(15, 23, 42, 0.055);
    }

    .role-hero .card-body {
      padding: 1rem !important;
    }

    .role-hero-badge {
      padding: .38rem .65rem;
      font-size: .78rem;
    }

    .role-hero h2 {
      font-size: 1.25rem;
      line-height: 1.25;
    }

    .role-hero p {
      font-size: .86rem;
      line-height: 1.45;
    }

    .role-hero-actions {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: .55rem;
      margin-top: 1rem;
    }

    .role-hero-actions .btn {
      min-height: 2.35rem;
      padding: .5rem .55rem;
      border-radius: .75rem;
      font-size: .8rem;
      line-height: 1.2;
    }

    .role-stat-card,
    .role-list-card {
      padding: .9rem;
    }

    .role-stat-label {
      font-size: .68rem;
      letter-spacing: .04em;
    }

    .role-stat-value {
      margin-top: .5rem;
      font-size: 1.65rem;
    }

    .role-stat-note {
      margin-top: .35rem;
      font-size: .75rem;
      line-height: 1.35;
    }

    .role-list-card h5 {
      font-size: 1rem;
    }

    .role-list-soft {
      font-size: .82rem;
      line-height: 1.4;
    }

    .role-list {
      gap: .6rem;
      margin-top: .75rem;
    }

    .role-list-item {
      display: grid;
      grid-template-columns: minmax(0, 1fr) auto;
      align-items: start;
      gap: .65rem;
      padding: .75rem;
      border-radius: .75rem;
    }

    .role-list-item-title {
      font-size: .92rem;
      line-height: 1.35;
    }

    .role-list-item-meta,
    .role-list-item-sub {
      font-size: .76rem;
      line-height: 1.4;
    }

    .role-list-item .badge {
      align-self: start;
      min-width: 2rem;
      text-align: center;
    }

    .role-list-item .btn {
      align-self: start;
      padding: .35rem .55rem;
      font-size: .75rem;
    }
  }

  @media (max-width: 379.98px) {
    .role-hero-actions {
      grid-template-columns: 1fr;
    }

    .role-stat-note {
      display: none;
    }
  }
</style>
@endsection

@section('content')
@php
  $attendanceBadges = [
    'hadir' => 'success',
    'terlambat' => 'warning',
    'izin' => 'info',
    'sakit' => 'danger',
    'belum_absen' => 'secondary',
  ];
@endphp

<div class="role-dashboard">
  @include('partials.app-breadcrumb', [
    'items' => [
      ['label' => 'Dashboard', 'current' => true],
    ],
  ])

  <div class="card role-hero">
    <div class="card-body p-4 p-xl-5">
      <div class="role-hero-badge">Admin Area</div>
      <div class="row g-4 align-items-end mt-1">
        <div class="col-12">
          <h2 class="text-white mb-2">Dashboard Operasional Intern</h2>
          <p class="text-white-50 mb-0">{{ $pageDescription }}</p>

          <div class="role-hero-actions">
            <a href="{{ route('admin.interns.index') }}" class="btn btn-light text-primary fw-semibold">Data Intern</a>
            <a href="{{ route('admin.attendances.index') }}" class="btn btn-outline-light fw-semibold">Monitoring Absensi</a>
            <a href="{{ route('admin.logbooks.index') }}" class="btn btn-outline-light fw-semibold">Monitoring Logbook</a>
            <a href="{{ route('admin.interns.import') }}" class="btn btn-outline-light fw-semibold">Import Intern</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-6 col-md-6 col-xl-3">
      <div class="card role-stat-card">
        <div class="role-stat-label">Total Intern</div>
        <div class="role-stat-value">{{ $totalInterns }}</div>
        <div class="role-stat-note">Semua peserta magang terdaftar.</div>
      </div>
    </div>
    <div class="col-6 col-md-6 col-xl-3">
      <div class="card role-stat-card">
        <div class="role-stat-label">Register</div>
        <div class="role-stat-value">{{ $adminOnboarding['register'] }}</div>
        <div class="role-stat-note">Belum melewati approval admin.</div>
      </div>
    </div>
    <div class="col-6 col-md-6 col-xl-3">
      <div class="card role-stat-card">
        <div class="role-stat-label">Melengkapi Data</div>
        <div class="role-stat-value">{{ $adminOnboarding['completing'] }}</div>
        <div class="role-stat-note">Sudah approve, onboarding belum selesai.</div>
      </div>
    </div>
    <div class="col-6 col-md-6 col-xl-3">
      <div class="card role-stat-card">
        <div class="role-stat-label">Logbook Bulan Ini</div>
        <div class="role-stat-value">{{ $logbookThisMonth }}</div>
        <div class="role-stat-note">Laporan yang masuk bulan berjalan.</div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-xl-5">
      <div class="card role-list-card">
        <h5>Status Absensi Hari Ini</h5>
        <p class="role-list-soft mb-0">Ringkasan kehadiran seluruh intern untuk hari ini.</p>
        <div class="row g-3 mt-1">
          @foreach ($adminAttendanceSummary as $item)
            <div class="col-6 col-md-6">
              <div class="role-list-item">
                <div>
                  <div class="role-list-item-title">{{ $item['label'] }}</div>
                  <div class="role-list-item-meta">Status absensi harian</div>
                </div>
                <span class="badge bg-label-{{ $attendanceBadges[$item['key']] ?? 'secondary' }} rounded-pill">{{ $item['count'] }}</span>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="col-xl-7">
      <div class="card role-list-card">
        <div class="role-list-header">
          <div>
            <h5>Logbook Terbaru</h5>
            <p class="role-list-soft mb-0">Aktivitas logbook terbaru dari intern.</p>
          </div>
          <a href="{{ route('admin.logbooks.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="role-list">
          @forelse ($recentLogbooks as $logbook)
            <div class="role-list-item">
              <div>
                <div class="role-list-item-title">{{ $logbook->intern->user->name ?? $logbook->intern->name }}</div>
                <div class="role-list-item-meta">
                  {{ $logbook->intern->division->name ?? 'Tanpa Divisi' }} • {{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('d M Y') }}
                </div>
                <div class="role-list-item-sub">{{ \Illuminate\Support\Str::limit($logbook->uraian_aktivitas, 120) }}</div>
              </div>
              <a href="{{ route('admin.logbooks.show', $logbook) }}" class="btn btn-sm btn-outline-primary">Detail</a>
            </div>
          @empty
            <div class="role-list-item">
              <div class="role-list-item-title">Belum ada logbook terbaru.</div>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-xl-6">
      <div class="card role-list-card">
        <h5>Distribusi Divisi</h5>
        <p class="role-list-soft mb-0">Jumlah intern aktif per divisi.</p>
        <div class="role-list">
          @forelse ($divisionSnapshots as $division)
            <div class="role-list-item">
              <div>
                <div class="role-list-item-title">{{ $division->name }}</div>
                <div class="role-list-item-meta">{{ $division->interns_count }} intern terdaftar</div>
              </div>
              <span class="badge bg-label-primary rounded-pill">{{ $division->interns_count }}</span>
            </div>
          @empty
            <div class="role-list-item">
              <div class="role-list-item-title">Belum ada divisi dengan intern.</div>
            </div>
          @endforelse
        </div>
      </div>
    </div>

    <div class="col-xl-6">
      <div class="card role-list-card">
        <h5>Intern Terbaru</h5>
        <p class="role-list-soft mb-0">Peserta magang yang paling baru masuk ke sistem.</p>
        <div class="role-list">
          @forelse ($recentInterns as $intern)
            <div class="role-list-item">
              <div>
                <div class="role-list-item-title">{{ $intern->user->name ?? $intern->name }}</div>
                <div class="role-list-item-meta">{{ $intern->division->name ?? 'Tanpa Divisi' }} • {{ ucfirst($intern->type ?? 'intern') }}</div>
                <div class="role-list-item-sub">
                  Status onboarding:
                  @if ($intern->registration_status === 'approved' && $intern->hasCompletedProfile() && $intern->hasCompletedDocuments())
                    Aktif
                  @elseif ($intern->registration_status === 'approved')
                    Melengkapi Data
                  @else
                    Register
                  @endif
                </div>
              </div>
              <a href="{{ route('admin.interns.show', $intern) }}" class="btn btn-sm btn-outline-primary">Detail</a>
            </div>
          @empty
            <div class="role-list-item">
              <div class="role-list-item-title">Belum ada intern terbaru.</div>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
