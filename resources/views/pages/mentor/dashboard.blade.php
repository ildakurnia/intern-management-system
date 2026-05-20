@extends('layouts/contentNavbarLayout')

@section('title', 'Mentor Dashboard')

@section('page-style')
<style>
  .role-dashboard {
    display: grid;
    gap: 1rem;
    font-family: var(--bs-body-font-family);
  }

  .role-dashboard :is(h1, h2, h3, h4, h5, h6, p, small, span, a, button, strong, label, .badge) {
    font-family: inherit;
  }

  .role-dashboard .card {
    border: 1px solid rgba(148, 163, 184, 0.14);
    border-radius: 1.5rem;
    box-shadow: 0 16px 42px rgba(15, 23, 42, 0.06);
    background: rgba(255, 255, 255, 0.96);
  }

  .role-hero {
    overflow: hidden;
    border: 0;
    color: #fff;
    min-height: 16rem;
    background:
      radial-gradient(circle at top right, rgba(255,255,255,0.18), transparent 26%),
      radial-gradient(circle at bottom left, rgba(125, 115, 255, 0.22), transparent 30%),
      linear-gradient(135deg, #2f27c7 0%, #4f46e5 52%, #625cf2 100%) !important;
  }

  .role-hero .card-body {
    position: relative;
    z-index: 1;
  }

  .role-hero-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.35fr) minmax(15rem, 0.82fr);
    gap: 1.25rem;
    align-items: stretch;
  }

  .role-hero-copy {
    display: flex;
    flex-direction: column;
    min-width: 0;
  }

  .role-hero-copy h2 {
    margin-bottom: .45rem;
    font-size: clamp(1.9rem, 3vw, 2.7rem);
    font-weight: 700;
    letter-spacing: -0.04em;
    line-height: 1.06;
  }

  .role-hero-copy p {
    font-size: 1rem;
    line-height: 1.6;
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
    letter-spacing: 0;
  }

  .role-hero-badge-group {
    display: flex;
    flex-wrap: wrap;
    gap: .65rem;
  }

  .role-hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: .75rem;
    margin-top: 1.5rem;
  }

  .role-hero-actions .btn {
    border-radius: 1rem;
    min-height: 2.9rem;
    font-weight: 700;
    letter-spacing: 0;
    transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease, border-color .18s ease, color .18s ease;
  }

  .mentor-hero-btn-logbook {
    background: #f4f5ff;
    color: #4f46e5;
    border: 1px solid rgba(255,255,255,.24);
    box-shadow: 0 10px 22px rgba(255,255,255,.08);
  }

  .mentor-hero-btn-logbook:hover,
  .mentor-hero-btn-logbook:focus {
    color: #4338ca;
    background: #ffffff;
    border-color: rgba(255,255,255,.32);
    box-shadow: 0 12px 24px rgba(15, 23, 42, 0.1);
    transform: translateY(-1px);
  }

  .mentor-hero-btn-attendance {
    color: #fff;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.32);
  }

  .mentor-hero-btn-attendance:hover,
  .mentor-hero-btn-attendance:focus {
    color: #fff;
    background: rgba(255,255,255,.14);
    border-color: rgba(255,255,255,.42);
    box-shadow: 0 12px 24px rgba(15, 23, 42, 0.14);
    transform: translateY(-1px);
  }

  .role-hero-panel {
    align-self: stretch;
    display: grid;
    gap: .85rem;
    padding: 1rem;
    border-radius: 1.25rem;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.14);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
  }

  .role-hero-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    color: rgba(255,255,255,.86);
    font-size: .82rem;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
  }

  .role-hero-panel-grid {
    display: grid;
    gap: .75rem;
  }

  .role-hero-panel-item {
    padding: .9rem .95rem;
    border-radius: 1rem;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.12);
  }

  .role-hero-panel-item span {
    display: block;
    color: rgba(255,255,255,.7);
    font-size: .78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
  }

  .role-hero-panel-item strong {
    display: block;
    margin-top: .35rem;
    color: #fff;
    font-size: 1.15rem;
    font-weight: 800;
    letter-spacing: -.03em;
  }

  .role-stat-card {
    padding: 1.25rem 1.35rem;
    height: 100%;
    overflow: hidden;
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

  .role-list-item-title {
    color: #1f2937;
    font-weight: 700;
  }

  .role-list-item-meta {
    margin-top: .2rem;
    color: #64748b;
    font-size: .84rem;
  }

  .role-list-item-sub {
    margin-top: .45rem;
    color: #475569;
    font-size: .84rem;
    line-height: 1.55;
  }

  .mentor-status-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 1rem;
    margin-top: 1rem;
  }

  .mentor-status-pill {
    position: relative;
    padding: .9rem .9rem .85rem;
    border: 1px solid rgba(148, 163, 184, 0.16);
    border-radius: 1.15rem;
    background: linear-gradient(180deg, rgba(248, 250, 252, 0.96), rgba(244, 247, 255, 0.86));
    text-align: left;
    overflow: hidden;
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.05);
    display: flex;
    flex-direction: column;
    gap: .5rem;
  }

  .mentor-status-pill::before {
    content: '';
    position: absolute;
    inset: 0 0 auto 0;
    height: 0.28rem;
    background: linear-gradient(90deg, #4f46e5, #7c3aed);
  }

  .mentor-status-pill.top-success::before {
    background: linear-gradient(90deg, #22c55e, #4ade80);
  }

  .mentor-status-pill.top-warning::before {
    background: linear-gradient(90deg, #f59e0b, #fbbf24);
  }

  .mentor-status-pill.top-info::before {
    background: linear-gradient(90deg, #0ea5e9, #38bdf8);
  }

  .mentor-status-pill.top-danger::before {
    background: linear-gradient(90deg, #ef4444, #fb7185);
  }

  .mentor-status-pill.top-secondary::before {
    background: linear-gradient(90deg, #94a3b8, #cbd5e1);
  }

  .mentor-status-pill-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .6rem;
    margin-bottom: 0;
  }

  .mentor-status-pill-icon {
    width: 2rem;
    height: 2rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: .8rem;
    flex-shrink: 0;
    font-size: .92rem;
  }

  .mentor-status-pill-icon.is-success {
    background: rgba(34, 197, 94, 0.14);
    color: #059669;
  }

  .mentor-status-pill-icon.is-warning {
    background: rgba(245, 158, 11, 0.14);
    color: #d97706;
  }

  .mentor-status-pill-icon.is-info {
    background: rgba(14, 165, 233, 0.14);
    color: #0284c7;
  }

  .mentor-status-pill-icon.is-danger {
    background: rgba(239, 68, 68, 0.14);
    color: #dc2626;
  }

  .mentor-status-pill-icon.is-secondary {
    background: rgba(148, 163, 184, 0.14);
    color: #64748b;
  }

  .mentor-status-pill .badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    max-width: 100%;
    white-space: normal;
    line-height: 1.2;
    text-align: center;
    padding: .38rem .58rem;
    font-size: .68rem;
    letter-spacing: .02em;
  }

  .mentor-status-pill-value {
    color: #161f39;
    font-size: 1.75rem;
    font-weight: 800;
    line-height: 1;
    margin-top: 0;
  }

  .mentor-status-pill-label {
    margin-top: -.05rem;
    color: #64748b;
    font-size: .74rem;
    font-weight: 700;
    line-height: 1.2;
  }

  .mentor-focus-list {
    display: grid;
    gap: .85rem;
    margin-top: 1rem;
  }

  .mentor-focus-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1rem 1.05rem;
    border: 1px solid rgba(148, 163, 184, 0.16);
    border-radius: 1rem;
    background: rgba(248, 250, 252, 0.72);
  }

  html[data-bs-theme="dark"] .role-dashboard .card {
    background: rgba(24, 28, 42, 0.88);
    border-color: rgba(148, 163, 184, 0.16);
    box-shadow: 0 18px 45px rgba(0, 0, 0, 0.24);
    color: #e2e8f0;
  }

  html[data-bs-theme="dark"] .role-hero {
    background:
      radial-gradient(circle at top right, rgba(255,255,255,0.12), transparent 26%),
      radial-gradient(circle at bottom left, rgba(125, 115, 255, 0.18), transparent 30%),
      linear-gradient(135deg, #172554 0%, #1e3a8a 48%, #4338ca 100%) !important;
  }

  html[data-bs-theme="dark"] .role-hero-panel {
    background: rgba(15, 23, 42, 0.28);
    border-color: rgba(255,255,255,.12);
  }

  html[data-bs-theme="dark"] .role-hero-panel-header {
    color: rgba(226,232,240,.82);
  }

  html[data-bs-theme="dark"] .role-hero-panel-item {
    background: rgba(255,255,255,.05);
    border-color: rgba(255,255,255,.08);
  }

  html[data-bs-theme="dark"] .mentor-hero-btn-logbook {
    background: #eef2ff;
    color: #4f46e5;
    border-color: rgba(255,255,255,.14);
    box-shadow: 0 10px 18px rgba(0,0,0,.12);
  }

  html[data-bs-theme="dark"] .mentor-hero-btn-logbook:hover,
  html[data-bs-theme="dark"] .mentor-hero-btn-logbook:focus {
    background: #ffffff;
    color: #4338ca;
    border-color: rgba(255,255,255,.22);
  }

  html[data-bs-theme="dark"] .mentor-hero-btn-attendance {
    background: rgba(255,255,255,.06);
    border-color: rgba(255,255,255,.2);
  }

  html[data-bs-theme="dark"] .role-stat-label,
  html[data-bs-theme="dark"] .role-list-soft,
  html[data-bs-theme="dark"] .role-list-item-meta,
  html[data-bs-theme="dark"] .role-list-item-sub,
  html[data-bs-theme="dark"] .mentor-status-pill-label,
  html[data-bs-theme="dark"] .role-stat-note {
    color: #94a3b8;
  }

  html[data-bs-theme="dark"] .role-stat-value,
  html[data-bs-theme="dark"] .role-list-item-title,
  html[data-bs-theme="dark"] .mentor-status-pill-value {
    color: #f8fafc;
  }

  html[data-bs-theme="dark"] .role-list-item,
  html[data-bs-theme="dark"] .mentor-focus-item,
  html[data-bs-theme="dark"] .mentor-status-pill {
    background: rgba(255, 255, 255, 0.04);
    border-color: rgba(148, 163, 184, 0.14);
  }

  html[data-bs-theme="dark"] .mentor-status-pill::before {
    opacity: 0.9;
  }

  html[data-bs-theme="dark"] .mentor-status-pill-icon.is-success {
    background: rgba(34, 197, 94, 0.16);
    color: #86efac;
  }

  html[data-bs-theme="dark"] .mentor-status-pill-icon.is-warning {
    background: rgba(245, 158, 11, 0.16);
    color: #fbbf24;
  }

  html[data-bs-theme="dark"] .mentor-status-pill-icon.is-info {
    background: rgba(14, 165, 233, 0.16);
    color: #7dd3fc;
  }

  html[data-bs-theme="dark"] .mentor-status-pill-icon.is-danger {
    background: rgba(239, 68, 68, 0.16);
    color: #fda4af;
  }

  html[data-bs-theme="dark"] .mentor-status-pill-icon.is-secondary {
    background: rgba(148, 163, 184, 0.14);
    color: #cbd5e1;
  }

  html[data-bs-theme="dark"] .mentor-status-pill .badge {
    box-shadow: none;
  }

  html[data-bs-theme="dark"] .mentor-status-pill-label {
    color: #94a3b8;
  }

  html[data-bs-theme="dark"] .mentor-status-pill-value {
    color: #f8fafc;
  }

  html[data-bs-theme="dark"] .mentor-focus-icon.is-warning {
    background: rgba(245, 158, 11, 0.16);
    color: #fbbf24;
  }

  html[data-bs-theme="dark"] .mentor-focus-icon.is-info {
    background: rgba(14, 165, 233, 0.16);
    color: #7dd3fc;
  }

  html[data-bs-theme="dark"] .mentor-focus-icon.is-success {
    background: rgba(34, 197, 94, 0.16);
    color: #86efac;
  }

  .mentor-focus-copy {
    display: flex;
    align-items: center;
    gap: .8rem;
  }

  .mentor-focus-icon {
    width: 2.6rem;
    height: 2.6rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: .95rem;
    flex-shrink: 0;
    font-size: 1rem;
  }

  .mentor-focus-icon.is-warning {
    background: #fff4d9;
    color: #d97706;
  }

  .mentor-focus-icon.is-info {
    background: #eaf2ff;
    color: #2563eb;
  }

  .mentor-focus-icon.is-success {
    background: #e9fbf2;
    color: #059669;
  }

  @media (max-width: 1199.98px) {
    .mentor-status-grid {
      grid-template-columns: repeat(3, minmax(0, 1fr));
    }
  }

  @media (max-width: 767.98px) {
    .role-hero-layout {
      grid-template-columns: 1fr;
    }

    .mentor-status-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
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

  $attendanceIcons = [
    'hadir' => 'ri-checkbox-circle-line',
    'terlambat' => 'ri-time-line',
    'izin' => 'ri-file-list-3-line',
    'sakit' => 'ri-heart-pulse-line',
    'belum_absen' => 'ri-user-unfollow-line',
  ];

  $mentorNeedsAttention = ($mentorOnboarding['register'] ?? 0) + ($mentorOnboarding['completing'] ?? 0);
  $mentorAttendanceToday = collect($mentorAttendanceSummary)->sum('count');
@endphp

<div class="role-dashboard">
  @include('partials.app-breadcrumb', [
    'items' => [
      ['label' => 'Dashboard', 'current' => true],
    ],
  ])

  <div class="card role-hero">
    <div class="card-body p-4 p-xl-5">
      <div class="role-hero-layout">
        <div class="role-hero-copy">
          <div class="role-hero-badge-group">
            <div class="role-hero-badge">Ruang Bimbingan</div>
            <div class="role-hero-badge">{{ $mentorDivisionName }}</div>
          </div>

          <h2 class="text-white mb-2">Dashboard Mentor {{ $mentorDivisionName }}</h2>
          <p class="text-white-50 mb-0">Pantau logbook dan absensi intern bimbingan Anda secara ringkas dan terarah.</p>

          <div class="role-hero-actions">
            <a href="{{ route('mentor.logbooks.index') }}" class="btn mentor-hero-btn-logbook">Monitoring Logbook</a>
            <a href="{{ route('mentor.attendances.index') }}" class="btn mentor-hero-btn-attendance">Monitoring Absensi</a>
          </div>
        </div>

        <div class="role-hero-panel">
          <div class="role-hero-panel-header">
            <span>Status Cepat</span>
            <i class="ri ri-dashboard-line"></i>
          </div>
          <div class="role-hero-panel-grid">
            <div class="role-hero-panel-item">
              <span>Total Intern</span>
              <strong>{{ $totalInterns }}</strong>
            </div>
            <div class="role-hero-panel-item">
              <span>Perlu Bimbingan</span>
              <strong>{{ $mentorNeedsAttention }}</strong>
            </div>
            <div class="role-hero-panel-item">
              <span>Absensi Hari Ini</span>
              <strong>{{ $mentorAttendanceToday }}</strong>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-md-6 col-xl-3">
      <div class="card role-stat-card">
        <div class="role-stat-label">Total Intern</div>
        <div class="role-stat-value">{{ $totalInterns }}</div>
        <div class="role-stat-note">Semua intern di divisi bimbingan.</div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card role-stat-card">
        <div class="role-stat-label">Register</div>
        <div class="role-stat-value">{{ $mentorOnboarding['register'] }}</div>
        <div class="role-stat-note">Belum selesai tahap approval.</div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card role-stat-card">
        <div class="role-stat-label">Melengkapi Data</div>
        <div class="role-stat-value">{{ $mentorOnboarding['completing'] }}</div>
        <div class="role-stat-note">Perlu menyelesaikan profil dan berkas.</div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card role-stat-card">
        <div class="role-stat-label">Logbook Bulan Ini</div>
        <div class="role-stat-value">{{ $logbookThisMonth }}</div>
        <div class="role-stat-note">Laporan dari intern divisi Anda.</div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-xl-7">
        <div class="card role-list-card">
          <h5>Status Absensi Hari Ini</h5>
          <p class="role-list-soft mb-0">Ringkasan kehadiran intern bimbingan Anda hari ini.</p>
        <div class="mentor-status-grid">
          @foreach ($mentorAttendanceSummary as $item)
            @php
              $tone = $attendanceBadges[$item['key']] ?? 'secondary';
              $icon = $attendanceIcons[$item['key']] ?? 'ri-pie-chart-2-line';
              $pillToneClass = match ($tone) {
                'success' => 'top-success',
                'warning' => 'top-warning',
                'info' => 'top-info',
                'danger' => 'top-danger',
                default => 'top-secondary',
              };
            @endphp
            <div class="mentor-status-pill {{ $pillToneClass }}">
              <div class="mentor-status-pill-top">
                <span class="mentor-status-pill-icon is-{{ $tone }}">
                  <i class="ri {{ $icon }}"></i>
                </span>
                <span class="badge bg-label-{{ $tone }} rounded-pill">{{ $item['label'] }}</span>
              </div>
              <div class="mentor-status-pill-value">{{ $item['count'] }}</div>
              <div class="mentor-status-pill-label">Intern Hari Ini</div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="col-xl-5">
      <div class="card role-list-card">
        <h5>Fokus Mentor Hari Ini</h5>
        <p class="role-list-soft mb-0">Area yang paling perlu perhatian dari sisi bimbingan.</p>
        <div class="mentor-focus-list">
          <div class="mentor-focus-item">
            <div class="mentor-focus-copy">
              <span class="mentor-focus-icon is-warning"><i class="ri ri-time-line"></i></span>
              <div>
                <div class="role-list-item-title">Intern tahap Register</div>
                <div class="role-list-item-meta">{{ $mentorOnboarding['register'] }} intern belum siap dipantau penuh.</div>
              </div>
            </div>
            <span class="badge bg-label-warning rounded-pill">{{ $mentorOnboarding['register'] }}</span>
          </div>

          <div class="mentor-focus-item">
            <div class="mentor-focus-copy">
              <span class="mentor-focus-icon is-info"><i class="ri ri-file-list-3-line"></i></span>
              <div>
                <div class="role-list-item-title">Intern Melengkapi Data</div>
                <div class="role-list-item-meta">{{ $mentorOnboarding['completing'] }} intern masih perlu profil atau berkas.</div>
              </div>
            </div>
            <span class="badge bg-label-info rounded-pill">{{ $mentorOnboarding['completing'] }}</span>
          </div>

          <div class="mentor-focus-item">
            <div class="mentor-focus-copy">
              <span class="mentor-focus-icon is-success"><i class="ri ri-checkbox-circle-line"></i></span>
              <div>
                <div class="role-list-item-title">Intern Aktif</div>
                <div class="role-list-item-meta">{{ $mentorOnboarding['active'] }} intern siap dipantau lewat logbook dan absensi.</div>
              </div>
            </div>
            <span class="badge bg-label-success rounded-pill">{{ $mentorOnboarding['active'] }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
