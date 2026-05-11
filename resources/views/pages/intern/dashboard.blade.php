@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard Intern')

@php
  use Illuminate\Support\Str;

  $now = now();
  $greeting = match (true) {
      $now->hour < 11 => 'Selamat pagi',
      $now->hour < 15 => 'Selamat siang',
      $now->hour < 18 => 'Selamat sore',
      default => 'Selamat malam',
  };

  $todayStatusLabel = $todayAttendance?->status_label ?? 'Belum Absen';
  $todayStatusBadge = $todayAttendance?->status_badge_class ?? 'secondary';
  $completionBadge = $profileCompleteness >= 100 ? 'success' : ($profileCompleteness >= 50 ? 'primary' : 'warning');
  $periodLabel = $internPeriod['start'] && $internPeriod['end']
      ? $internPeriod['start'].' - '.$internPeriod['end']
      : 'Periode belum ditentukan';
  $institutionLabel = $intern?->institution_label ?? '-';
  $divisionLabel = $intern?->division?->name ?? 'Divisi belum ditentukan';
  $registrationLabel = match ($intern?->registration_status) {
      'approved' => 'disetujui',
      'pending' => 'menunggu verifikasi',
      'rejected' => 'perlu perbaikan',
      default => 'belum diperbarui',
  };
  $internTypeLabel = match ($intern?->type) {
      'mahasiswa' => 'Mahasiswa',
      'siswa' => 'Siswa',
      default => 'Intern',
  };

  if (! $hasCompletedProfile) {
      $primaryActionUrl = route('intern.profile.edit');
      $primaryActionLabel = 'Lengkapi Profil';
  } elseif (! $hasCompletedDocuments) {
      $primaryActionUrl = route('intern.documents.edit');
      $primaryActionLabel = 'Upload Berkas';
  } else {
      $primaryActionUrl = route('intern.logbooks.index');
      $primaryActionLabel = 'Buat Logbook';
  }

  $todayAttendanceMeta = 'Absensi hari ini belum tercatat.';
  if ($todayAttendance?->check_in_at) {
      $todayAttendanceMeta = 'Check in '.$todayAttendance->check_in_at->format('H:i').' WIB';

      if ($todayAttendance?->check_out_at) {
          $todayAttendanceMeta .= ' - Check out '.$todayAttendance->check_out_at->format('H:i').' WIB';
      }
  } elseif (in_array($todayAttendance?->status, ['izin', 'sakit', 'tidak_hadir'], true)) {
      $todayAttendanceMeta = 'Status pengajuan hari ini: '.$todayStatusLabel.'.';
  }

  $firstPendingStepIndex = collect($onboardingSteps)->search(fn ($step) => $step['status'] !== 'completed');

  $onboardingViewSteps = collect($onboardingSteps)->values()->map(function ($step, $index) use ($intern, $firstPendingStepIndex) {
      $isCompleted = $step['status'] === 'completed';
      $isActive = $firstPendingStepIndex !== false && $index === $firstPendingStepIndex;
      $isLocked = ! $isCompleted && ! $isActive;

      $detail = $step['desc'];

      if ($isCompleted) {
          $completedAt = match ($step['title']) {
              'Akun Dibuat' => $intern?->registered_at ?? $intern?->user?->created_at,
              'Lengkapi Profil' => $intern?->profile_completed_at,
              'Unggah Berkas' => $intern?->documents_completed_at,
              default => null,
          };

          $detail = $completedAt
              ? 'Selesai pada '.$completedAt->translatedFormat('d M Y')
              : 'Langkah ini sudah selesai';
      } elseif ($isActive) {
          $detail = match ($step['title']) {
              'Lengkapi Profil' => 'Data diri, kontak, dan alamat perlu dilengkapi',
              'Unggah Berkas' => 'KTP, kartu pelajar, BPJS, dan surat pengantar masih diperlukan',
              'Verifikasi Akhir' => 'Menunggu seluruh persyaratan selesai sebelum diverifikasi',
              default => $step['desc'],
          };
      } elseif ($isLocked) {
          $detail = match ($step['title']) {
              'Verifikasi Akhir' => 'Menunggu berkas diunggah',
              default => 'Selesaikan langkah sebelumnya terlebih dahulu',
          };
      }

      $action = null;
      if ($isActive) {
          $action = match ($step['title']) {
              'Lengkapi Profil' => ['label' => 'Lengkapi Profil', 'url' => route('intern.profile.edit')],
              'Unggah Berkas' => ['label' => 'Upload Sekarang', 'url' => route('intern.documents.edit')],
              default => null,
          };
      }

      return [
          'title' => $step['title'],
          'detail' => $detail,
          'completed' => $isCompleted,
          'active' => $isActive,
          'locked' => $isLocked,
          'action' => $action,
      ];
  });

  $todayActivities = $recentLogbooks
      ->filter(fn ($logbook) => $logbook->tanggal?->isSameDay($now))
      ->values();
  $featuredActivity = $todayActivities->first();
@endphp

@section('page-style')
  <style>
    .intern-dashboard {
      display: grid;
      gap: 1.5rem;
    }

    .intern-dashboard-card {
      border: 1px solid rgba(148, 163, 184, 0.14);
      border-radius: 1.6rem;
      background: rgba(255, 255, 255, 0.96);
      box-shadow: 0 18px 44px rgba(15, 23, 42, 0.06);
    }

    .intern-dashboard-hero {
      position: relative;
      overflow: hidden;
      padding: 1.85rem;
      border-radius: 1.9rem;
      background:
        radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), transparent 24%),
        linear-gradient(135deg, #2136c7 0%, #3846df 45%, #6c63ff 100%);
      color: #fff;
      box-shadow: 0 28px 52px rgba(43, 62, 202, 0.24);
    }

    .intern-dashboard-hero::after {
      content: '';
      position: absolute;
      inset: auto -4rem -4rem auto;
      width: 13rem;
      height: 13rem;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.18), transparent 65%);
      pointer-events: none;
    }

    .intern-dashboard-hero-grid {
      position: relative;
      z-index: 1;
      display: grid;
      grid-template-columns: minmax(0, 1.4fr) minmax(18rem, 0.9fr);
      gap: 1.5rem;
      align-items: start;
    }

    .intern-dashboard-pills {
      display: flex;
      flex-wrap: wrap;
      gap: 0.7rem;
      margin-bottom: 1.15rem;
    }

    .intern-dashboard-pill {
      display: inline-flex;
      align-items: center;
      gap: 0.55rem;
      padding: 0.72rem 1rem;
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.14);
      color: rgba(255, 255, 255, 0.92);
      font-size: 0.86rem;
      font-weight: 700;
      letter-spacing: -0.01em;
    }

    .intern-dashboard-pill-dot {
      width: 0.8rem;
      height: 0.8rem;
      border-radius: 50%;
      background: currentColor;
      box-shadow: 0 0 0 0.35rem rgba(255, 255, 255, 0.08);
      opacity: 0.95;
    }

    .intern-dashboard-title {
      margin: 0;
      color: rgba(255, 255, 255, 0.98);
      font-size: clamp(1.8rem, 3vw, 2.7rem);
      font-weight: 800;
      letter-spacing: -0.04em;
      line-height: 1.02;
      text-shadow: 0 8px 22px rgba(15, 23, 42, 0.18);
    }

    .intern-dashboard-subtitle {
      max-width: 38rem;
      margin: 0.9rem 0 1.35rem;
      color: rgba(255, 255, 255, 0.82);
      font-size: 1rem;
      line-height: 1.65;
    }

    .intern-dashboard-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      margin-bottom: 1.35rem;
      color: rgba(255, 255, 255, 0.78);
      font-size: 0.92rem;
    }

    .intern-dashboard-meta span {
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
    }

    .intern-dashboard-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.8rem;
    }

    .intern-dashboard-btn-primary,
    .intern-dashboard-btn-secondary {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.55rem;
      min-height: 3rem;
      padding: 0.9rem 1.2rem;
      border-radius: 0.95rem;
      font-weight: 700;
      text-decoration: none;
      transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .intern-dashboard-btn-primary {
      background: #fff;
      color: #3143d0;
      box-shadow: 0 16px 28px rgba(15, 23, 42, 0.14);
    }

    .intern-dashboard-btn-secondary {
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.16);
      color: #fff;
    }

    .intern-dashboard-btn-primary:hover,
    .intern-dashboard-btn-secondary:hover {
      transform: translateY(-1px);
      color: inherit;
    }

    .intern-dashboard-aside {
      padding: 1.2rem;
      border-radius: 1.5rem;
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.14);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
    }

    .intern-dashboard-aside-label {
      margin: 0 0 0.35rem;
      color: rgba(255, 255, 255, 0.68);
      font-size: 0.78rem;
      font-weight: 700;
      letter-spacing: 0.08em;
      text-transform: uppercase;
    }

    .intern-dashboard-aside-time {
      margin: 0;
      font-size: clamp(3rem, 5vw, 4.35rem);
      line-height: 0.95;
      font-weight: 800;
      letter-spacing: -0.06em;
    }

    .intern-dashboard-aside-date {
      margin: 0.7rem 0 1rem;
      color: rgba(255, 255, 255, 0.9);
      font-size: 1.02rem;
      font-weight: 700;
      line-height: 1.35;
    }

    .intern-dashboard-aside-note {
      margin: 0;
      color: rgba(255, 255, 255, 0.74);
      font-size: 0.92rem;
      line-height: 1.6;
    }

    .intern-dashboard-summary-grid {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 1rem;
    }

    .intern-summary-card {
      padding: 1.2rem;
    }

    .intern-summary-icon {
      width: 2.8rem;
      height: 2.8rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 1rem;
      margin-bottom: 1rem;
      font-size: 1.2rem;
    }

    .intern-summary-value {
      margin: 0;
      color: #1f2a44;
      font-size: 1.55rem;
      font-weight: 800;
      letter-spacing: -0.03em;
      line-height: 1.1;
    }

    .intern-summary-label {
      margin: 0.42rem 0 0.35rem;
      color: #64748b;
      font-size: 0.9rem;
      font-weight: 600;
    }

    .intern-summary-meta {
      margin: 0;
      color: #94a3b8;
      font-size: 0.82rem;
      line-height: 1.55;
    }

    .intern-panel {
      padding: 1.4rem;
    }

    .intern-panel-header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: 1.1rem;
    }

    .intern-panel-title {
      margin: 0;
      color: #334155;
      font-size: 1.55rem;
      font-weight: 700;
      letter-spacing: -0.03em;
    }

    .intern-panel-subtitle {
      margin: 0.35rem 0 0;
      color: #94a3b8;
      font-size: 0.9rem;
    }

    .intern-soft-link {
      color: #2481ff;
      font-size: 0.92rem;
      font-weight: 700;
      text-decoration: none;
    }

    .intern-focus-grid {
      display: grid;
      grid-template-columns: minmax(0, 1.45fr) minmax(18rem, 0.92fr);
      gap: 1rem;
      align-items: stretch;
    }

    .intern-activity-panel,
    .intern-onboarding-panel {
      padding: 0;
      overflow: hidden;
    }

    .intern-activity-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      padding: 1.2rem 1.35rem;
      border-bottom: 1px solid rgba(148, 163, 184, 0.16);
    }

    .intern-activity-body {
      min-height: 24rem;
      padding: 1.5rem;
    }

    .intern-empty-activity {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 21rem;
      text-align: center;
    }

    .intern-empty-activity img {
      width: min(13rem, 100%);
      margin: 0 auto 1.25rem;
    }

    .intern-empty-activity h4 {
      margin: 0;
      color: #1e293b;
      font-size: 1.55rem;
      font-weight: 800;
      letter-spacing: -0.03em;
    }

    .intern-empty-activity p {
      max-width: 26rem;
      margin: 0.7rem auto 1.25rem;
      color: #64748b;
      font-size: 0.98rem;
      line-height: 1.7;
    }

    .intern-empty-activity-action {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.55rem;
      min-height: 3rem;
      padding: 0.85rem 1.5rem;
      border-radius: 0.9rem;
      background: linear-gradient(135deg, #1d4ed8, #2563eb);
      color: #fff;
      font-weight: 700;
      text-decoration: none;
      box-shadow: 0 16px 28px rgba(37, 99, 235, 0.2);
    }

    .intern-activity-list {
      display: grid;
      gap: 0.8rem;
    }

    .intern-activity-featured {
      display: block;
      margin-bottom: 0.95rem;
      padding: 1.15rem 1.15rem 1rem;
      border-radius: 1.15rem;
      border: 1px solid rgba(96, 165, 250, 0.18);
      background: linear-gradient(180deg, rgba(248, 250, 255, 0.95), #fff);
      text-decoration: none;
      transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
    }

    .intern-activity-featured:hover {
      transform: translateY(-2px);
      border-color: rgba(59, 130, 246, 0.24);
      box-shadow: 0 16px 28px rgba(15, 23, 42, 0.08);
    }

    .intern-activity-featured-title {
      margin: 0 0 0.6rem;
      color: #1e293b;
      font-size: 1.05rem;
      font-weight: 800;
      letter-spacing: -0.02em;
    }

    .intern-activity-featured-text {
      margin: 0 0 0.9rem;
      color: #475569;
      font-size: 0.96rem;
      line-height: 1.75;
    }

    .intern-activity-featured-meta {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.8rem;
      color: #64748b;
      font-size: 0.84rem;
      font-weight: 700;
    }

    .intern-activity-item {
      display: block;
      padding: 1rem 1.1rem;
      border-radius: 1rem;
      border: 1px solid rgba(148, 163, 184, 0.14);
      background: #fff;
      text-decoration: none;
      transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
    }

    .intern-activity-item:hover {
      transform: translateY(-2px);
      border-color: rgba(59, 130, 246, 0.18);
      box-shadow: 0 16px 28px rgba(15, 23, 42, 0.08);
    }

    .intern-activity-item-date {
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      margin-bottom: 0.55rem;
      color: #94a3b8;
      font-size: 0.76rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.04em;
    }

    .intern-activity-item-date i {
      width: 2rem;
      height: 2rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 0.8rem;
      color: #3350dd;
      background: rgba(59, 91, 255, 0.1);
      font-size: 1rem;
    }

    .intern-activity-item-text {
      margin: 0 0 0.9rem;
      color: #334155;
      font-size: 0.96rem;
      line-height: 1.7;
    }

    .intern-activity-item-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.8rem;
      color: #64748b;
      font-size: 0.84rem;
      font-weight: 700;
    }

    .intern-activity-item-link {
      color: #2563eb;
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
    }

    .intern-activity-count {
      margin-top: 0.9rem;
      color: #94a3b8;
      font-size: 0.82rem;
      font-weight: 600;
    }

    .intern-onboarding-panel {
      padding: 1.35rem;
    }

    .intern-onboarding-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .intern-onboarding-title {
      margin: 0;
      color: #1e293b;
      font-size: 1.55rem;
      font-weight: 800;
      letter-spacing: -0.03em;
    }

    .intern-onboarding-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0.45rem 0.8rem;
      border-radius: 999px;
      background: rgba(37, 99, 235, 0.1);
      color: #2563eb;
      font-size: 0.76rem;
      font-weight: 800;
    }

    .intern-onboarding-summary {
      margin: 0 0 1rem;
      color: #94a3b8;
      font-size: 0.88rem;
      line-height: 1.6;
    }

    .intern-onboarding-progress {
      width: 100%;
      height: 0.42rem;
      margin-bottom: 1.25rem;
      overflow: hidden;
      border-radius: 999px;
      background: rgba(148, 163, 184, 0.16);
    }

    .intern-onboarding-progress > span {
      display: block;
      height: 100%;
      border-radius: inherit;
      background: linear-gradient(90deg, #22c55e, #10b981);
    }

    .intern-timeline {
      position: relative;
      display: grid;
      gap: 1.15rem;
      padding-left: 0.45rem;
    }

    .intern-timeline::before {
      content: '';
      position: absolute;
      top: 0.9rem;
      bottom: 1rem;
      left: 0.72rem;
      width: 2px;
      background: rgba(203, 213, 225, 0.9);
    }

    .intern-timeline-item {
      position: relative;
      display: grid;
      grid-template-columns: auto minmax(0, 1fr);
      gap: 0.9rem;
      align-items: start;
    }

    .intern-timeline-marker {
      position: relative;
      z-index: 1;
      width: 1.55rem;
      height: 1.55rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-top: 0.1rem;
      border-radius: 50%;
      font-size: 0.88rem;
      box-shadow: 0 0 0 0.5rem rgba(255, 255, 255, 0.96);
    }

    .intern-timeline-marker.is-completed {
      background: #22c55e;
      color: #fff;
      box-shadow:
        0 0 0 0.5rem rgba(255, 255, 255, 0.96),
        0 0 18px rgba(34, 197, 94, 0.22);
    }

    .intern-timeline-marker.is-active {
      border: 3px solid #2563eb;
      background: #fff;
      color: #2563eb;
    }

    .intern-timeline-marker.is-locked {
      background: #e5e7eb;
      color: #64748b;
    }

    .intern-timeline-step-title {
      margin: 0;
      color: #1e293b;
      font-size: 1.03rem;
      font-weight: 800;
      letter-spacing: -0.02em;
    }

    .intern-timeline-step-title.is-active {
      color: #2563eb;
    }

    .intern-timeline-step-title.is-locked {
      color: #9ca3af;
    }

    .intern-timeline-step-detail {
      margin: 0.18rem 0 0;
      color: #64748b;
      font-size: 0.88rem;
      line-height: 1.6;
    }

    .intern-timeline-step-detail.is-locked {
      color: #9ca3af;
    }

    .intern-timeline-action {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-top: 0.7rem;
      padding: 0.68rem 1rem;
      border-radius: 0.82rem;
      color: #1d4ed8;
      background: rgba(37, 99, 235, 0.08);
      border: 1px solid rgba(37, 99, 235, 0.14);
      font-size: 0.86rem;
      font-weight: 800;
      text-decoration: none;
    }

    @media (max-width: 1399.98px) {
      .intern-dashboard-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media (max-width: 1199.98px) {
      .intern-dashboard-hero-grid,
      .intern-focus-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 767.98px) {
      .intern-dashboard-hero,
      .intern-panel,
      .intern-onboarding-card,
      .intern-summary-card {
        padding: 1.1rem;
      }

      .intern-dashboard-summary-grid {
        grid-template-columns: 1fr;
      }

      .intern-dashboard-title {
        font-size: 1.6rem;
      }

      .intern-dashboard-aside-time {
        font-size: 2.8rem;
      }
    }
  </style>
@endsection

@section('content')
  <div class="intern-dashboard">
    <section class="intern-dashboard-hero">
      <div class="intern-dashboard-hero-grid">
        <div>
          <div class="intern-dashboard-pills">
            <span class="intern-dashboard-pill">
              <span class="intern-dashboard-pill-dot"></span>
              {{ $internTypeLabel }}
            </span>
            <span class="intern-dashboard-pill">
              <span class="intern-dashboard-pill-dot"></span>
              {{ strtoupper($todayStatusLabel) }}
            </span>
          </div>

          <h1 class="intern-dashboard-title">{{ $greeting }}, {{ auth()->user()->name }}!</h1>
          <p class="intern-dashboard-subtitle">
            Pantau aktivitas magang, absensi, logbook, dan progres onboarding Anda dalam satu dashboard yang lebih ringkas.
          </p>

          <div class="intern-dashboard-meta">
            <span><i class="ri ri-building-line"></i>{{ $divisionLabel }}</span>
            <span><i class="ri ri-graduation-cap-line"></i>{{ $institutionLabel }}</span>
            <span><i class="ri ri-calendar-schedule-line"></i>{{ $periodLabel }}</span>
          </div>

          <div class="intern-dashboard-actions">
            <a href="{{ $primaryActionUrl }}" class="intern-dashboard-btn-primary">
              <i class="ri ri-add-line"></i>
              {{ $primaryActionLabel }}
            </a>
            <a href="{{ route('intern.attendances.index') }}" class="intern-dashboard-btn-secondary">
              <i class="ri ri-calendar-check-line"></i>
              Lihat Aktivitas
            </a>
          </div>
        </div>

        <div class="intern-dashboard-aside">
          <p class="intern-dashboard-aside-label">Status Hari Ini</p>
          <p class="intern-dashboard-aside-time">{{ $now->format('H:i') }}</p>
          <p class="intern-dashboard-aside-date">{{ $now->translatedFormat('l, d F Y') }}</p>
          <p class="intern-dashboard-aside-note">
            {{ $todayAttendanceMeta }} Jam kerja aktif 08:00 - 17:00 WIB.
          </p>
        </div>
      </div>
    </section>

    <section class="intern-dashboard-summary-grid">
      <article class="intern-dashboard-card intern-summary-card">
        <span class="intern-summary-icon text-primary bg-label-primary">
          <i class="ri ri-draft-line"></i>
        </span>
        <h3 class="intern-summary-value">{{ $totalLogbooks }}</h3>
        <p class="intern-summary-label">Total Logbook</p>
        <p class="intern-summary-meta">{{ $logbookThisMonth }} laporan dibuat pada bulan ini.</p>
      </article>

      <article class="intern-dashboard-card intern-summary-card">
        <span class="intern-summary-icon text-success bg-label-success">
          <i class="ri ri-calendar-line"></i>
        </span>
        <h3 class="intern-summary-value">{{ $internPeriod['daysPassed'] }}</h3>
        <p class="intern-summary-label">Hari Magang Berjalan</p>
        <p class="intern-summary-meta">{{ $periodLabel }} • {{ $internPeriod['percentage'] }}% perjalanan magang.</p>
      </article>

      <article class="intern-dashboard-card intern-summary-card">
        <span class="intern-summary-icon text-warning bg-label-warning">
          <i class="ri ri-briefcase-4-line"></i>
        </span>
        <h3 class="intern-summary-value">{{ $divisionLabel }}</h3>
        <p class="intern-summary-label">Divisi Penempatan</p>
        <p class="intern-summary-meta">{{ $institutionLabel }} • status akun {{ $registrationLabel }}.</p>
      </article>

      <article class="intern-dashboard-card intern-summary-card">
        <span class="intern-summary-icon text-{{ $todayStatusBadge }} bg-label-{{ $todayStatusBadge }}">
          <i class="ri ri-fingerprint-line"></i>
        </span>
        <h3 class="intern-summary-value">{{ $todayStatusLabel }}</h3>
        <p class="intern-summary-label">Status Absensi Hari Ini</p>
        <p class="intern-summary-meta">{{ $todayAttendanceMeta }}</p>
      </article>
    </section>

    <section class="intern-focus-grid">
      <div class="intern-dashboard-card intern-activity-panel">
        <div class="intern-activity-header">
          <div>
            <h2 class="intern-panel-title">Aktivitas Terbaru</h2>
            <p class="intern-panel-subtitle">Menampilkan satu logbook hari ini agar dashboard tetap ringkas dan fokus.</p>
          </div>
          <a href="{{ route('intern.logbooks.index') }}" class="intern-soft-link">Lihat semua</a>
        </div>

        <div class="intern-activity-body">
          @if ($featuredActivity)
            <a href="{{ route('intern.logbooks.show', $featuredActivity->id) }}" class="intern-activity-featured">
              <div class="intern-activity-item-date">
                <i class="ri ri-file-list-3-line"></i>
                <span>{{ $featuredActivity->tanggal->translatedFormat('d M Y') }}</span>
              </div>
              <h3 class="intern-activity-featured-title">Aktivitas Paling Baru</h3>
              <p class="intern-activity-featured-text">{{ Str::limit($featuredActivity->uraian_aktivitas, 220) }}</p>
              <div class="intern-activity-featured-meta">
                <span>{{ Str::limit($featuredActivity->pembelajaran_diperoleh, 64) }}</span>
                <span class="intern-activity-item-link">
                  Lihat detail
                  <i class="ri ri-arrow-right-line"></i>
                </span>
              </div>
            </a>
          @else
            <div class="intern-empty-activity">
              <div>
                <img src="{{ asset('assets/img/front-pages/icons/google-docs.png') }}" alt="Aktivitas kosong">
                <h4>Belum ada logbook hari ini</h4>
                <p>Isi satu logbook harian hari ini agar aktivitas terbaru langsung muncul di dashboard.</p>
                <a href="{{ route('intern.logbooks.index') }}" class="intern-empty-activity-action">
                  <i class="ri ri-calendar-line"></i>
                  Buka Kalender Logbook
                </a>
              </div>
            </div>
          @endif
        </div>
      </div>

      <div class="intern-dashboard-card intern-onboarding-panel">
        <div class="intern-onboarding-header">
          <h2 class="intern-onboarding-title">Onboarding</h2>
          <span class="intern-onboarding-badge">{{ $profileCompleteness }}% Lengkap</span>
        </div>

        <p class="intern-onboarding-summary">Kelengkapan profil dan tahapan aktivasi akun intern.</p>

        <div class="intern-onboarding-progress">
          <span style="width: {{ $profileCompleteness }}%;"></span>
        </div>

        <div class="intern-timeline">
          @foreach ($onboardingViewSteps as $step)
            <div class="intern-timeline-item">
              <span class="intern-timeline-marker {{ $step['completed'] ? 'is-completed' : ($step['active'] ? 'is-active' : 'is-locked') }}">
                @if ($step['completed'])
                  <i class="ri ri-check-line"></i>
                @elseif ($step['active'])
                  <span style="width: 0.42rem; height: 0.42rem; border-radius: 50%; background: currentColor;"></span>
                @else
                  <i class="ri ri-lock-2-line"></i>
                @endif
              </span>

              <div>
                <h3 class="intern-timeline-step-title {{ $step['active'] ? 'is-active' : ($step['locked'] ? 'is-locked' : '') }}">
                  {{ $step['title'] }}
                </h3>
                <p class="intern-timeline-step-detail {{ $step['locked'] ? 'is-locked' : '' }}">{{ $step['detail'] }}</p>

                @if ($step['action'])
                  <div>
                    <a href="{{ $step['action']['url'] }}" class="intern-timeline-action">{{ $step['action']['label'] }}</a>
                  </div>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  </div>
@endsection
