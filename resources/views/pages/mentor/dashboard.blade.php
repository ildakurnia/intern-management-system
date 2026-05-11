@extends('layouts/contentNavbarLayout')

@section('title', 'Mentor Dashboard')

@section('page-style')
<style>
  .role-dashboard {
    display: grid;
    gap: 1rem;
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
      radial-gradient(circle at bottom left, rgba(125, 115, 255, 0.22), transparent 30%),
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
    gap: .85rem;
    margin-top: 1rem;
  }

  .mentor-status-pill {
    padding: 1rem .9rem;
    border: 1px solid rgba(148, 163, 184, 0.16);
    border-radius: 1rem;
    background: rgba(248, 250, 252, 0.72);
    text-align: center;
    overflow: hidden;
  }

  .mentor-status-pill .badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    max-width: 100%;
    white-space: normal;
    line-height: 1.2;
    text-align: center;
  }

  .mentor-status-pill-value {
    color: #161f39;
    font-size: 1.7rem;
    font-weight: 800;
    line-height: 1;
    margin-top: .7rem;
  }

  .mentor-status-pill-label {
    margin-top: .55rem;
    color: #64748b;
    font-size: .8rem;
    font-weight: 700;
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
@endphp

<div class="role-dashboard">
  @include('partials.app-breadcrumb', [
    'items' => [
      ['label' => 'Dashboard', 'current' => true],
    ],
  ])

  <div class="card role-hero">
    <div class="card-body p-4 p-xl-5">
      <div class="role-hero-badge-group">
        <div class="role-hero-badge">Ruang Bimbingan</div>
        <div class="role-hero-badge">{{ $mentorDivisionName }}</div>
      </div>
      <div class="row g-4 align-items-end mt-1">
        <div class="col-12">
          <h2 class="text-white mb-2">Dashboard Bimbingan {{ $mentorDivisionName }}</h2>
          <p class="text-white-50 mb-0">{{ $pageDescription }}</p>

          <div class="role-hero-actions">
            <a href="{{ route('mentor.logbooks.index') }}" class="btn btn-light text-primary fw-semibold">Monitoring Logbook</a>
            <a href="{{ route('mentor.attendances.index') }}" class="btn btn-outline-light fw-semibold">Monitoring Absensi</a>
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
            <div class="mentor-status-pill">
              <span class="badge bg-label-{{ $attendanceBadges[$item['key']] ?? 'secondary' }} rounded-pill">{{ $item['label'] }}</span>
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
