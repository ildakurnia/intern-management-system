@extends('layouts/contentNavbarLayout')

@section('title', 'Absensi Saya')

@section('page-style')
<style>
  .attendance-page {
    --attendance-primary: #3f37db;
    --attendance-primary-soft: #eef0ff;
    --attendance-border: rgba(85, 91, 120, 0.14);
    --attendance-text-soft: #8b90a7;
  }

  .attendance-page .card {
    border: 1px solid var(--attendance-border);
    border-radius: 1.5rem;
    box-shadow: 0 18px 45px rgba(27, 33, 58, 0.06);
  }

  .attendance-hero {
    overflow: hidden;
    border: 0;
    color: #fff;
    min-height: 19rem;
    background:
      radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), transparent 24%),
      radial-gradient(circle at bottom left, rgba(125, 115, 255, 0.28), transparent 30%),
      linear-gradient(135deg, #2f27c7 0%, #4f46e5 52%, #625cf2 100%);
  }

  .attendance-badge-soft {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.55rem 0.9rem;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 700;
    color: #eef2ff;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.14);
    backdrop-filter: blur(14px);
  }

  .attendance-badge-soft .dot {
    width: 0.55rem;
    height: 0.55rem;
    border-radius: 999px;
    background: #6ee7b7;
    box-shadow: 0 0 0 0.3rem rgba(110, 231, 183, 0.18);
  }

  .attendance-badge-soft.is-muted .dot {
    background: rgba(255, 255, 255, 0.7);
    box-shadow: none;
  }

  .attendance-live-time {
    font-size: clamp(2.8rem, 7vw, 5.1rem);
    line-height: 0.95;
    letter-spacing: -0.05em;
    font-weight: 800;
    margin: 0;
  }

  .attendance-hero-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-top: 1rem;
  }

  .attendance-hero-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.7rem 0.95rem;
    border-radius: 1rem;
    color: #eef2ff;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.12);
  }

  .attendance-hero-pill i {
    font-size: 1rem;
  }

  .attendance-hero-location {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    margin-top: 0.7rem;
    color: rgba(238, 242, 255, 0.82);
    font-size: 1.05rem;
  }

  .attendance-hero-side {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: center;
    height: 100%;
  }

  .attendance-hero-cta {
    width: min(100%, 23rem);
    background: rgba(255, 255, 255, 0.98);
    border: 0;
    border-radius: 1.45rem;
    color: var(--attendance-primary);
    box-shadow: 0 18px 40px rgba(18, 22, 51, 0.18);
  }

  .attendance-hero-cta .btn,
  .attendance-hero-cta button {
    border-radius: 1.45rem;
    min-height: 5.2rem;
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--attendance-primary);
    background: transparent;
    border: 0;
  }

  .attendance-hero-live-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    margin-top: 1.4rem;
    padding: 0.8rem 1rem;
    border-radius: 999px;
    color: rgba(255, 255, 255, 0.88);
    background: rgba(55, 44, 191, 0.38);
    border: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05);
  }

  .attendance-hero-secondary-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end;
    gap: 0.75rem;
    margin-top: 0.85rem;
  }

  .attendance-hero-secondary-actions .btn {
    border-radius: 999px;
    color: #eef2ff;
    border-color: rgba(255, 255, 255, 0.24);
    background: rgba(255, 255, 255, 0.08);
  }

  .attendance-section-title {
    font-size: 1.85rem;
    line-height: 1.1;
    margin-bottom: 0.35rem;
    color: #1c2340;
  }

  .attendance-section-link {
    color: var(--attendance-primary);
    font-weight: 700;
    text-decoration: none;
  }

  .attendance-section-link:hover {
    color: #2f27c7;
  }

  .attendance-kpi-card {
    padding: 1.65rem 1.35rem 1.2rem;
    height: 100%;
    background: #fff;
    border: 1px solid rgba(181, 186, 214, 0.46) !important;
    box-shadow: 0 12px 30px rgba(30, 36, 68, 0.04) !important;
  }

  .attendance-kpi-icon {
    width: 3.3rem;
    height: 3.3rem;
    border-radius: 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.35rem;
    border: 1px solid rgba(227, 231, 247, 0.9);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.92);
  }

  .attendance-kpi-icon-glyph {
    font-size: 1.32rem;
    line-height: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .attendance-kpi-icon--primary {
    background: #e9ebff;
    color: #4237ee;
  }

  .attendance-kpi-icon--success {
    background: #e9faef;
    color: #0d7a52;
  }

  .attendance-kpi-icon--warning {
    background: #fff4e1;
    color: #ef4444;
  }

  .attendance-kpi-icon--info {
    background: #eef9ff;
    color: #8a5608;
  }

  .attendance-kpi-icon--secondary {
    background: #e9eefb;
    color: #7e879e;
  }

  .attendance-kpi-card h3 {
    font-size: 3.05rem;
    line-height: 1;
    margin-bottom: 0;
    color: #111a32;
  }

  .attendance-kpi-card p,
  .attendance-soft-text {
    color: var(--attendance-text-soft);
  }

  .attendance-kpi-label {
    font-size: 0.78rem;
    letter-spacing: 0.11em;
    text-transform: uppercase;
    color: #28314f;
    margin-bottom: 0.6rem;
    font-weight: 500;
  }

  .attendance-kpi-value {
    display: flex;
    align-items: flex-end;
    gap: 0.45rem;
    margin-bottom: 0.75rem;
  }

  .attendance-kpi-unit {
    color: var(--attendance-text-soft);
    font-size: 1.08rem;
    line-height: 1.3;
    padding-bottom: 0.45rem;
  }

  .attendance-detail-card,
  .attendance-recent-card,
  .attendance-history-card {
    padding: 1.4rem;
    background: #fff;
  }

  .attendance-detail-card {
    padding: 1.55rem;
  }

  .attendance-recent-card {
    padding: 1.2rem;
  }

  .attendance-detail-hero {
    border-radius: 1.3rem;
    padding: 1.15rem 1.2rem;
    background: linear-gradient(135deg, #f5f6ff 0%, #fbfcff 100%);
    border: 1px solid rgba(92, 99, 146, 0.1);
  }

  .attendance-detail-grid {
    display: grid;
    gap: 0.9rem;
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .attendance-detail-item {
    border-radius: 1.15rem;
    padding: 1rem 1.05rem;
    border: 1px solid rgba(91, 97, 137, 0.12);
    background: #fff;
  }

  .attendance-detail-item small {
    display: block;
    color: var(--attendance-text-soft);
    margin-bottom: 0.25rem;
  }

  .attendance-detail-item strong {
    font-size: 1.2rem;
    color: #202844;
  }

  .attendance-recent-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
  }

  .attendance-recent-item {
    display: flex;
    align-items: center;
    gap: 0.95rem;
    padding: 0.9rem 0.95rem;
    border-radius: 1rem;
    border: 1px solid rgba(91, 97, 137, 0.12);
    background: #fff;
  }

  .attendance-recent-icon {
    width: 2.75rem;
    height: 2.75rem;
    border-radius: 0.95rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
  }

  .attendance-recent-item .fw-semibold {
    font-size: 0.98rem;
  }

  .attendance-recent-time {
    margin-left: auto;
    text-align: right;
    color: #222944;
    font-weight: 700;
    white-space: nowrap;
  }

  .attendance-inline-note {
    border-radius: 1.1rem;
    padding: 1rem 1.05rem;
    background: #f8f9ff;
    border: 1px dashed rgba(92, 99, 146, 0.2);
  }

  .attendance-filter-shell {
    border-radius: 1rem;
    padding: 0.8rem;
    background: linear-gradient(180deg, #fbfcff 0%, #f7f8ff 100%);
    border: 1px solid rgba(91, 97, 137, 0.1);
  }

  .attendance-filter-shell label {
    display: block;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.35rem;
    color: var(--attendance-text-soft);
  }

  .attendance-filter-shell .form-control,
  .attendance-filter-shell .form-select {
    min-height: 2.85rem;
  }

  .attendance-history-table thead th {
    font-size: 0.77rem;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: var(--attendance-text-soft);
    background: #f7f8fb;
    border-bottom: 0;
  }

  .attendance-history-table tbody tr {
    border-color: rgba(91, 97, 137, 0.1);
  }

  .attendance-detail-button {
    min-width: 5.7rem;
  }

  .attendance-preview-frame {
    border: 1px solid rgba(91, 97, 137, 0.12);
    border-radius: 1rem;
    overflow: hidden;
    background: #fff;
  }

  .attendance-preview-image {
    display: block;
    width: 100%;
    max-height: 320px;
    object-fit: contain;
    background: #f7f8fb;
  }

  .attendance-empty-state {
    border-radius: 1.2rem;
    padding: 1.25rem;
    border: 1px dashed rgba(91, 97, 137, 0.2);
    background: #fafbff;
  }

  .attendance-mobile-shell {
    display: none;
  }

  .attendance-mobile-card {
    border: 1px solid rgba(181, 186, 214, 0.38);
    border-radius: 1.15rem;
    background: #fff;
    box-shadow: 0 10px 24px rgba(30, 36, 68, 0.06);
    overflow: hidden;
  }

  html[data-bs-theme="dark"] .attendance-mobile-card {
    background: #2a2740;
    border-color: rgba(219, 223, 255, 0.12);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.24);
  }

  .attendance-mobile-card + .attendance-mobile-card {
    margin-top: 0.9rem;
  }

  .attendance-mobile-card-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0.95rem 1rem 0.8rem;
    border-bottom: 1px solid rgba(181, 186, 214, 0.22);
  }

  html[data-bs-theme="dark"] .attendance-mobile-card-head {
    border-bottom-color: rgba(219, 223, 255, 0.1);
  }

  .attendance-mobile-title {
    display: flex;
    align-items: center;
    gap: 0.7rem;
    min-width: 0;
  }

  .attendance-mobile-avatar {
    width: 2.65rem;
    height: 2.65rem;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: #ecefff;
    color: #4b46d8;
    font-size: 0.82rem;
    font-weight: 800;
  }

  html[data-bs-theme="dark"] .attendance-mobile-avatar {
    background: rgba(93, 91, 255, 0.18);
    color: #cbc7ff;
  }

  .attendance-mobile-name {
    color: var(--bs-heading-color);
    font-size: 0.98rem;
    font-weight: 700;
    line-height: 1.2;
  }

  .attendance-mobile-email {
    color: var(--bs-secondary-color);
    font-size: 0.79rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .attendance-mobile-body {
    padding: 0.9rem 1rem 1rem;
    display: grid;
    gap: 0.8rem;
  }

  .attendance-mobile-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.45rem;
  }

  .attendance-mobile-meta .badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-weight: 600;
  }

  .attendance-mobile-note {
    color: var(--bs-body-color);
    font-size: 0.87rem;
    line-height: 1.5;
  }

  .attendance-mobile-lines {
    display: grid;
    gap: 0.42rem;
    color: var(--bs-body-color);
    font-size: 0.87rem;
    line-height: 1.45;
  }

  .attendance-mobile-line {
    display: flex;
    align-items: flex-start;
    gap: 0.45rem;
  }

  .attendance-mobile-line i {
    margin-top: 0.12rem;
    color: var(--attendance-primary);
    flex-shrink: 0;
  }

  .attendance-mobile-summary-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.55rem;
  }

  .attendance-mobile-summary-box {
    border: 1px solid rgba(181, 186, 214, 0.38);
    border-radius: 1rem;
    padding: 0.95rem 0.9rem;
    background: #fff;
  }

  html[data-bs-theme="dark"] .attendance-mobile-summary-box {
    background: rgba(31, 33, 48, 0.74);
    border-color: rgba(219, 223, 255, 0.12);
  }

  .attendance-mobile-summary-label {
    color: var(--bs-secondary-color);
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 0.3rem;
  }

  .attendance-mobile-summary-value {
    color: var(--bs-heading-color);
    font-size: 1rem;
    font-weight: 800;
    line-height: 1.2;
  }

  .attendance-mobile-actions {
    display: grid;
    gap: 0.55rem;
  }

  .attendance-mobile-actions .btn {
    width: 100%;
    border-radius: 0.85rem;
  }

  .attendance-mobile-actions-compact {
    display: flex;
    gap: 0.55rem;
  }

  .attendance-mobile-actions-compact .btn {
    flex: 1 1 0;
    border-radius: 999px;
  }

  .attendance-history-mobile-list {
    display: none;
  }

  .attendance-history-mobile-card {
    border: 1px solid rgba(181, 186, 214, 0.38);
    border-radius: 1rem;
    background: #fff;
    box-shadow: 0 10px 24px rgba(30, 36, 68, 0.06);
    overflow: hidden;
  }

  html[data-bs-theme="dark"] .attendance-history-mobile-card {
    background: #2a2740;
    border-color: rgba(219, 223, 255, 0.12);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.24);
  }

  .attendance-history-mobile-card .card-header {
    background: transparent;
    padding: 0.9rem 1rem 0.75rem;
    border-bottom: 1px solid rgba(181, 186, 214, 0.22);
  }

  html[data-bs-theme="dark"] .attendance-history-mobile-card .card-header {
    border-bottom-color: rgba(219, 223, 255, 0.1);
  }

  .attendance-history-mobile-title {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    min-width: 0;
  }

  .attendance-history-mobile-avatar {
    width: 2.45rem;
    height: 2.45rem;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: #ecefff;
    color: #4b46d8;
    font-size: 0.82rem;
    font-weight: 800;
  }

  html[data-bs-theme="dark"] .attendance-history-mobile-avatar {
    background: rgba(93, 91, 255, 0.18);
    color: #cbc7ff;
  }

  .attendance-history-mobile-name {
    color: var(--bs-heading-color);
    font-size: 0.95rem;
    font-weight: 700;
    line-height: 1.25;
  }

  .attendance-history-mobile-email {
    color: var(--bs-secondary-color);
    font-size: 0.78rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .attendance-history-mobile-card .card-body {
    padding: 0.85rem 1rem 0.95rem;
    display: grid;
    gap: 0.65rem;
  }

  .attendance-history-mobile-meta-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.45rem;
  }

  .attendance-history-mobile-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    min-width: 0;
    padding: 0.55rem 0.75rem;
    border-radius: 0.9rem;
    background: rgba(109, 114, 255, 0.08);
    color: var(--bs-heading-color);
    font-size: 0.82rem;
    font-weight: 600;
  }

  html[data-bs-theme="dark"] .attendance-history-mobile-meta-item {
    background: rgba(109, 114, 255, 0.14);
  }

  .attendance-history-mobile-meta-item i {
    color: var(--attendance-primary);
    font-size: 0.95rem;
    flex-shrink: 0;
  }

  .attendance-history-mobile-status {
    color: var(--bs-secondary-color);
    font-size: 0.88rem;
    line-height: 1.45;
  }

  .attendance-history-mobile-standalone {
    display: grid;
    gap: 0.42rem;
    padding-top: 0.15rem;
  }

  .attendance-history-mobile-standalone .attendance-history-mobile-meta-item {
    width: 100%;
    justify-content: flex-start;
    background: transparent;
    padding: 0;
    color: var(--bs-body-color);
    font-weight: 500;
    border-radius: 0;
  }

  .attendance-history-mobile-stats {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.75rem;
  }

  .attendance-history-mobile-stat {
    border: 1px solid rgba(181, 186, 214, 0.34);
    border-radius: 0.95rem;
    padding: 0.9rem 1rem;
    background: rgba(250, 250, 255, 0.8);
  }

  .attendance-history-mobile-stat--full {
    grid-column: 1 / -1;
  }

  html[data-bs-theme="dark"] .attendance-history-mobile-stat {
    background: rgba(255, 255, 255, 0.03);
    border-color: rgba(219, 223, 255, 0.1);
  }

  .attendance-history-mobile-stat small {
    display: block;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    color: var(--bs-secondary-color);
    margin-bottom: 0.2rem;
  }

  .attendance-history-mobile-stat strong {
    display: block;
    color: var(--bs-heading-color);
    font-size: 0.95rem;
    line-height: 1.3;
  }

  .attendance-history-mobile-actions {
    display: flex;
  }

  .attendance-history-mobile-actions .btn {
    width: 100%;
    border-radius: 0.85rem;
  }

  .attendance-history-mobile-actions .btn i {
    font-size: 0.95rem;
  }

  .attendance-history-mobile-card .attendance-history-mobile-meta .badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-weight: 600;
  }

  .attendance-history-mobile-lines {
    display: grid;
    gap: 0.4rem;
    color: var(--bs-body-color);
    font-size: 0.86rem;
    line-height: 1.45;
  }

  .attendance-history-mobile-line {
    display: flex;
    align-items: flex-start;
    gap: 0.45rem;
  }

  .attendance-history-mobile-line i {
    margin-top: 0.1rem;
    color: var(--attendance-primary);
    flex-shrink: 0;
  }

  .attendance-geolocation-feedback {
    display: none;
  }

  .attendance-geolocation-feedback.is-visible {
    display: block;
  }

  @media (max-width: 991.98px) {
    .attendance-detail-grid {
      grid-template-columns: 1fr;
    }

    .attendance-hero-side {
      align-items: stretch;
      margin-top: 0.5rem;
    }

    .attendance-hero-cta {
      width: 100%;
    }

    .attendance-hero-secondary-actions {
      justify-content: flex-start;
    }

    .attendance-hero-live-pill {
      margin-top: 1rem;
      align-self: flex-end;
    }
  }

  @media (max-width: 767.98px) {
    .attendance-live-time {
      font-size: clamp(2.6rem, 17vw, 4.25rem);
    }

    .attendance-hero-location {
      font-size: 0.95rem;
    }

    .attendance-hero-live-pill {
      align-self: stretch;
      justify-content: center;
    }

    .attendance-history-table-wrap {
      display: none !important;
    }

    .attendance-history-mobile-list {
      display: grid;
      gap: 0.8rem;
    }

    .attendance-history-mobile-actions {
      flex-direction: column;
    }

    .attendance-mobile-shell {
      display: grid;
      gap: 0.85rem;
    }

    .attendance-hide-on-mobile {
      display: none !important;
    }
  }
</style>
@endsection

@section('content')
@php
  $now = now();
  $todayLabel = $now->locale('id')->translatedFormat('l, d F Y');
  $todayIso = $now->toIso8601String();
  $timeLabel = $now->format('H:i');
  $workWindowLabel = config('attendance.work_start_time').' - '.config('attendance.work_end_time').' WIB';
  $todayStatusLabel = $todayAttendance?->status_label ?? 'Belum Absen';
  $todayStatusBadge = $todayAttendance?->status_badge_class ?? 'secondary';
  $isLeaveSubmissionToday = $todayAttendance && in_array($todayAttendance->status, [\App\Models\Attendance::STATUS_PERMISSION, \App\Models\Attendance::STATUS_SICK], true);
  $currentWorkMinutes = $todayAttendance?->work_minutes ?? 0;

  if ($todayAttendance?->check_in_at && ! $todayAttendance?->check_out_at) {
      $currentWorkMinutes = $todayAttendance->check_in_at->diffInMinutes($now);
  }

  $formatMinutes = function (?int $minutes): string {
      if (! $minutes) {
          return '-';
      }

      $hours = intdiv($minutes, 60);
      $remainingMinutes = $minutes % 60;
      $parts = [];

      if ($hours > 0) {
          $parts[] = $hours.' jam';
      }

      if ($remainingMinutes > 0) {
          $parts[] = $remainingMinutes.' menit';
      }

      return $parts === [] ? '0 menit' : implode(' ', $parts);
  };

  $resolveWorkDurationLabel = function ($attendance) use ($now, $formatMinutes): string {
      if (! $attendance) {
          return '-';
      }

      if ($attendance->check_in_at && ! $attendance->check_out_at && in_array($attendance->status, [\App\Models\Attendance::STATUS_PRESENT, \App\Models\Attendance::STATUS_LATE], true)) {
          $liveMinutes = max(0, $attendance->check_in_at->diffInMinutes($now));

          return 'Aktif '.($liveMinutes > 0 ? $formatMinutes($liveMinutes) : '0 menit');
      }

      if ($attendance->work_minutes !== null) {
          return $attendance->work_minutes > 0 ? $formatMinutes($attendance->work_minutes) : '-';
      }

      return '-';
  };

  $attendanceStep = match (true) {
      ! $todayAttendance => 'Belum Check In',
      $todayAttendance->status === \App\Models\Attendance::STATUS_PERMISSION => 'Izin Hari Ini',
      $todayAttendance->status === \App\Models\Attendance::STATUS_SICK => 'Sakit Hari Ini',
      $todayAttendance->check_in_at && ! $todayAttendance->check_out_at => 'Sedang Bekerja',
      $todayAttendance->check_out_at => 'Absensi Selesai',
      $todayAttendance->status === \App\Models\Attendance::STATUS_ABSENT => 'Tidak Hadir',
      default => 'Status Tercatat',
  };

  $todayMessage = match (true) {
      ! $todayAttendance => 'Mulai hari kerja Anda dari sini. Check in saat tiba di kantor atau ajukan izin dan sakit bila berhalangan hadir.',
      $todayAttendance->status === \App\Models\Attendance::STATUS_LATE && ! $todayAttendance->check_out_at => 'Check in Anda sudah tercatat, namun melewati jam masuk. Lanjutkan pekerjaan Anda dan lakukan check out saat selesai.',
      $todayAttendance->status === \App\Models\Attendance::STATUS_LATE && $todayAttendance->check_out_at => 'Absensi hari ini lengkap dan sistem menandai Anda terlambat masuk.',
      $todayAttendance->status === \App\Models\Attendance::STATUS_PRESENT && ! $todayAttendance->check_out_at => 'Check in berhasil. Aktivitas hari ini sedang berjalan dan akan lengkap setelah Anda melakukan check out.',
      $todayAttendance->status === \App\Models\Attendance::STATUS_PRESENT && $todayAttendance->check_out_at => 'Absensi hari ini sudah lengkap. Kehadiran Anda tercatat dengan baik.',
      $todayAttendance->status === \App\Models\Attendance::STATUS_PERMISSION => 'Pengajuan izin hari ini sudah terekam dan dapat dipantau mentor maupun admin.',
      $todayAttendance->status === \App\Models\Attendance::STATUS_SICK => 'Pengajuan sakit hari ini sudah terekam dan dapat dipantau mentor maupun admin.',
      $todayAttendance->status === \App\Models\Attendance::STATUS_ABSENT => 'Sistem menandai hari ini sebagai tidak hadir karena tidak ada aktivitas absensi yang tercatat.',
      default => 'Status absensi hari ini sudah tercatat.',
  };

  $heroHint = match (true) {
      ! $todayAttendance => 'Belum ada aktivitas absensi hari ini.',
      $todayAttendance->check_in_at && ! $todayAttendance->check_out_at => 'Aktif sejak '.$todayAttendance->check_in_at->format('H:i').' WIB',
      $todayAttendance->check_out_at => 'Selesai pada '.$todayAttendance->check_out_at->format('H:i').' WIB',
      $todayAttendance->status === \App\Models\Attendance::STATUS_ABSENT => 'Ditandai otomatis oleh sistem.',
      default => 'Status hari ini sudah diperbarui.',
  };

  $heroLivePill = match (true) {
      ! $todayAttendance => 'Siap memulai hari kerja',
      $todayAttendance->check_in_at && ! $todayAttendance->check_out_at => $resolveWorkDurationLabel($todayAttendance),
      $todayAttendance->check_out_at => 'Total '.$todayAttendance->work_duration_label,
      $todayAttendance->status === \App\Models\Attendance::STATUS_LATE => 'Terlambat '.$todayAttendance->late_duration_label,
      default => $attendanceStep,
  };

  $validatedLocationLabel = $todayAttendance?->attendanceLocation?->name;
  $heroLocationLabel = match (true) {
      (bool) $validatedLocationLabel => $validatedLocationLabel,
      (bool) $todayAttendance => 'Lokasi belum tervalidasi',
      default => 'Lokasi akan dicek saat check in',
  };
  $heroLocationSuffix = '';
  $intern = auth()->user()?->intern;
  $internTypeLabel = match ($intern?->type) {
      'siswa' => 'Siswa',
      'mahasiswa' => 'Mahasiswa',
      default => 'Intern',
  };
  $internInitials = strtoupper(substr(auth()->user()->name ?? 'IM', 0, 2));
  $internDivisionLabel = $intern?->division?->name ?? '-';

  $recentEntries = $attendanceSummary['recentAttendances']->take(3);
  $statusIcons = [
      \App\Models\Attendance::STATUS_PRESENT => 'ri-checkbox-circle-line',
      \App\Models\Attendance::STATUS_LATE => 'ri-timer-flash-line',
      \App\Models\Attendance::STATUS_PERMISSION => 'ri-file-list-3-line',
      \App\Models\Attendance::STATUS_SICK => 'ri-heart-pulse-line',
      \App\Models\Attendance::STATUS_ABSENT => 'ri-close-circle-line',
  ];
  $monthlyInsightCards = [
      [
          'label' => 'TOTAL HARI',
          'value' => $attendanceSummary['attendanceThisMonth'],
          'unit' => 'Hari',
          'description' => 'Jumlah hari absensi yang sudah tercatat bulan ini.',
          'icon' => 'ri-calendar-line',
          'iconClass' => 'attendance-kpi-icon--primary',
      ],
      [
          'label' => 'TEPAT WAKTU',
          'value' => $attendanceSummary['attendanceStatusCounts'][\App\Models\Attendance::STATUS_PRESENT]['count'] ?? 0,
          'unit' => null,
          'description' => 'Kehadiran dengan check in sesuai jam kerja.',
          'icon' => 'ri-verified-badge-line',
          'iconClass' => 'attendance-kpi-icon--success',
      ],
      [
          'label' => 'TERLAMBAT',
          'value' => $attendanceSummary['attendanceStatusCounts'][\App\Models\Attendance::STATUS_LATE]['count'] ?? 0,
          'unit' => null,
          'description' => 'Kehadiran yang melewati batas jam masuk.',
          'icon' => 'ri-timer-flash-line',
          'iconClass' => 'attendance-kpi-icon--warning',
      ],
      [
          'label' => 'IZIN',
          'value' => $attendanceSummary['attendanceStatusCounts'][\App\Models\Attendance::STATUS_PERMISSION]['count'] ?? 0,
          'unit' => null,
          'description' => 'Pengajuan izin yang tersimpan pada sistem.',
          'icon' => 'ri-file-list-3-line',
          'iconClass' => 'attendance-kpi-icon--info',
      ],
      [
          'label' => 'SAKIT',
          'value' => $attendanceSummary['attendanceStatusCounts'][\App\Models\Attendance::STATUS_SICK]['count'] ?? 0,
          'unit' => null,
          'description' => 'Pengajuan sakit yang tercatat pada bulan ini.',
          'icon' => 'ri-heart-pulse-line',
          'iconClass' => 'attendance-kpi-icon--secondary',
      ],
  ];
@endphp

<div class="attendance-page row g-6">
  <div class="col-12">
    <div id="attendanceGeolocationFeedback" class="alert alert-danger mb-0 attendance-geolocation-feedback" role="alert"></div>
  </div>

  <div class="col-12 attendance-hide-on-mobile">
    <div class="card attendance-hero">
      <div class="card-body p-4 p-xl-5">
        <div class="row g-4 align-items-center">
          <div class="col-xl-7">
            <div class="d-flex flex-wrap gap-2">
              <span class="attendance-badge-soft">
                <span class="dot"></span>
                {{ strtoupper($attendanceStep) }}
              </span>
            </div>

            <div class="mt-4">
              <p class="attendance-live-time" id="attendanceLiveClock" data-now="{{ $todayIso }}">{{ $timeLabel }}</p>
              <div class="fs-2 fw-semibold mt-2">{{ $todayLabel }}</div>
              <div class="attendance-hero-location">
                <i class="ri ri-map-pin-line"></i>
                <span>{{ $heroLocationLabel }}{{ $heroLocationSuffix }} • Jam kerja {{ $workWindowLabel }}</span>
              </div>
            </div>
          </div>

          <div class="col-xl-5">
            <div class="attendance-hero-side">
              <div class="attendance-hero-cta">
                @if (! $todayAttendance)
                  <form action="{{ route('intern.attendances.check-in') }}" method="POST" data-attendance-geolocation-form>
                    @csrf
                    <input type="hidden" name="latitude">
                    <input type="hidden" name="longitude">
                    <input type="hidden" name="accuracy">
                    <button type="submit" class="btn btn-lg w-100">
                      <i class="ri ri-login-box-line me-2"></i> Check In Sekarang
                    </button>
                  </form>
                @elseif ($todayAttendance->check_in_at && ! $todayAttendance->check_out_at && in_array($todayAttendance->status, [\App\Models\Attendance::STATUS_PRESENT, \App\Models\Attendance::STATUS_LATE], true))
                  <form action="{{ route('intern.attendances.check-out') }}" method="POST" data-attendance-geolocation-form>
                    @csrf
                    <input type="hidden" name="latitude">
                    <input type="hidden" name="longitude">
                    <input type="hidden" name="accuracy">
                    <button type="submit" class="btn btn-lg w-100">
                      <i class="ri ri-logout-box-r-line me-2"></i> Check Out Sekarang
                    </button>
                  </form>
                @else
                  <a href="#riwayat-absensi" class="btn btn-lg w-100 d-flex align-items-center justify-content-center">
                    <i class="ri ri-time-line me-2"></i> Lihat Riwayat Absensi
                  </a>
                @endif
              </div>

              @if (! $todayAttendance)
                <div class="attendance-hero-secondary-actions">
                  <a href="{{ route('intern.attendances.submissions.create', 'izin') }}" class="btn btn-sm">
                    <i class="ri ri-file-list-3-line me-1"></i> Ajukan Izin
                  </a>
                  <a href="{{ route('intern.attendances.submissions.create', 'sakit') }}" class="btn btn-sm">
                    <i class="ri ri-heart-pulse-line me-1"></i> Ajukan Sakit
                  </a>
                </div>
              @endif

              <div class="attendance-hero-live-pill">
                <i class="ri ri-timer-line"></i>
                <span>{{ $heroLivePill }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if (session('status'))
    <div class="col-12">
      <div class="alert alert-success mb-0">{{ session('status') }}</div>
    </div>
  @endif

  @if ($errors->any())
    <div class="col-12">
      <div class="alert alert-danger mb-0">
        <ul class="mb-0 ps-4">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  @endif

  <div class="col-12 d-md-none">
    <div class="attendance-mobile-shell">
      <div class="attendance-mobile-card">
        <div class="attendance-mobile-card-head">
          <div class="attendance-mobile-title min-w-0">
            <div class="attendance-mobile-avatar">{{ $internInitials }}</div>
            <div class="min-w-0">
              <div class="attendance-mobile-name">{{ auth()->user()->name }}</div>
              <div class="attendance-mobile-email">{{ auth()->user()->email }}</div>
            </div>
          </div>
          <span class="badge bg-label-{{ $todayStatusBadge }} rounded-pill flex-shrink-0">{{ $attendanceStep }}</span>
        </div>

        <div class="attendance-mobile-body">
          <div class="attendance-mobile-meta">
            <span class="badge bg-label-info rounded-pill">{{ $internDivisionLabel }}</span>
            <span class="badge bg-label-primary rounded-pill">{{ $internTypeLabel }}</span>
          </div>

          <div class="attendance-mobile-note">{{ $todayMessage }}</div>

          <div class="attendance-mobile-lines">
            <div class="attendance-mobile-line">
              <i class="ri ri-login-box-line"></i>
              <span>Check In: {{ $todayAttendance?->check_in_at?->format('H:i') ?? '-' }}</span>
            </div>
            <div class="attendance-mobile-line">
              <i class="ri ri-logout-box-r-line"></i>
              <span>Check Out: {{ $todayAttendance?->check_out_at?->format('H:i') ?? '-' }}</span>
            </div>
          </div>

          <div class="attendance-mobile-summary-grid">
            <div class="attendance-mobile-summary-box">
              <div class="attendance-mobile-summary-label">Total Kehadiran</div>
              <div class="attendance-mobile-summary-value">{{ $attendanceSummary['attendanceThisMonth'] }}/{{ now()->daysInMonth }} Hari</div>
            </div>
            <div class="attendance-mobile-summary-box">
              <div class="attendance-mobile-summary-label">Status Hari Ini</div>
              <div class="attendance-mobile-summary-value">{{ $todayStatusLabel }}</div>
            </div>
          </div>

          <div class="attendance-mobile-actions">
            @if (! $todayAttendance)
              <form action="{{ route('intern.attendances.check-in') }}" method="POST" data-attendance-geolocation-form>
                @csrf
                <input type="hidden" name="latitude">
                <input type="hidden" name="longitude">
                <input type="hidden" name="accuracy">
                <button type="submit" class="btn btn-lg btn-primary w-100">
                  <i class="ri ri-login-box-line me-2"></i> Check In Sekarang
                </button>
              </form>
              <div class="attendance-mobile-actions-compact">
                <a href="{{ route('intern.attendances.submissions.create', 'izin') }}" class="btn btn-outline-primary">
                  <i class="ri ri-file-list-3-line me-1"></i> Izin
                </a>
                <a href="{{ route('intern.attendances.submissions.create', 'sakit') }}" class="btn btn-outline-primary">
                  <i class="ri ri-heart-pulse-line me-1"></i> Sakit
                </a>
              </div>
            @elseif ($todayAttendance->check_in_at && ! $todayAttendance->check_out_at && in_array($todayAttendance->status, [\App\Models\Attendance::STATUS_PRESENT, \App\Models\Attendance::STATUS_LATE], true))
              <form action="{{ route('intern.attendances.check-out') }}" method="POST" data-attendance-geolocation-form>
                @csrf
                <input type="hidden" name="latitude">
                <input type="hidden" name="longitude">
                <input type="hidden" name="accuracy">
                <button type="submit" class="btn btn-lg btn-primary w-100">
                  <i class="ri ri-logout-box-r-line me-2"></i> Check Out Sekarang
                </button>
              </form>
              <a href="#riwayat-absensi" class="btn btn-outline-secondary w-100">
                <i class="ri ri-eye-line me-1"></i> Lihat Detail
              </a>
            @else
              <a href="#riwayat-absensi" class="btn btn-lg btn-primary w-100 d-flex align-items-center justify-content-center">
                <i class="ri ri-eye-line me-2"></i> Lihat Detail
              </a>
            @endif
          </div>
        </div>
      </div>

      <div class="attendance-mobile-card">
        <div class="attendance-mobile-card-head">
          <div>
            <div class="attendance-mobile-name">Ringkasan Hari Ini</div>
            <div class="attendance-mobile-email">{{ $todayLabel }}</div>
          </div>
          <span class="badge bg-label-{{ $todayStatusBadge }} rounded-pill flex-shrink-0">{{ $attendanceStep }}</span>
        </div>
        <div class="attendance-mobile-body">
          <div class="attendance-mobile-summary-grid">
            <div class="attendance-mobile-summary-box">
              <div class="attendance-mobile-summary-label">Lokasi</div>
              <div class="attendance-mobile-summary-value">{{ $heroLocationLabel }}</div>
            </div>
            <div class="attendance-mobile-summary-box">
              <div class="attendance-mobile-summary-label">Jam Kerja</div>
              <div class="attendance-mobile-summary-value">{{ $workWindowLabel }}</div>
            </div>
          </div>
          <div class="attendance-mobile-summary-grid">
            <div class="attendance-mobile-summary-box">
              <div class="attendance-mobile-summary-label">Durasi</div>
              <div class="attendance-mobile-summary-value">{{ $heroLivePill }}</div>
            </div>
            <div class="attendance-mobile-summary-box">
              <div class="attendance-mobile-summary-label">Akurasi</div>
              <div class="attendance-mobile-summary-value">{{ $todayAttendance?->check_in_accuracy ? number_format((float) $todayAttendance->check_in_accuracy, 0, ',', '.') . ' m' : '-' }}</div>
            </div>
          </div>
          <a href="#riwayat-absensi" class="btn btn-outline-primary w-100">
            <i class="ri ri-list-check-2 me-1"></i> Lihat Riwayat
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-8 col-lg-7 attendance-hide-on-mobile">
    <div class="card attendance-detail-card h-100">
      <div class="d-flex flex-column flex-md-row align-items-md-start justify-content-between gap-3 mb-4">
        <div>
          <h5 class="mb-1">Status Hari Ini</h5>
          <small class="attendance-soft-text">{{ $todayLabel }}</small>
        </div>
        <span class="badge rounded-pill bg-label-{{ $todayStatusBadge }}">{{ $attendanceStep }}</span>
      </div>

      <div class="attendance-detail-hero mb-4">
        <div class="row g-3 align-items-center">
          <div class="col-md-8">
            <small class="text-uppercase fw-semibold attendance-soft-text">Ringkasan Hari Ini</small>
            <h2 class="mt-2 mb-2">{{ $todayStatusLabel }}</h2>
            <p class="mb-0 attendance-soft-text">{{ $todayMessage }}</p>
          </div>
          <div class="col-md-4">
            <div class="text-md-end">
              <small class="attendance-soft-text d-block mb-1">Progress</small>
              <strong class="fs-3 text-dark">{{ $attendanceStep }}</strong>
            </div>
          </div>
        </div>
      </div>

      @if ($todayAttendance)
        <div class="attendance-detail-grid">
          <div class="attendance-detail-item">
            <small>Check In</small>
            <strong>{{ $todayAttendance->check_in_at?->format('H:i') ?? '-' }}</strong>
            <div class="attendance-soft-text small mt-1">Waktu masuk tercatat</div>
          </div>
          <div class="attendance-detail-item">
            <small>Check Out</small>
            <strong>{{ $todayAttendance->check_out_at?->format('H:i') ?? '-' }}</strong>
            <div class="attendance-soft-text small mt-1">Waktu pulang tercatat</div>
          </div>
          <div class="attendance-detail-item">
            <small>Durasi Kerja</small>
            <strong>{{ $resolveWorkDurationLabel($todayAttendance) }}</strong>
            <div class="attendance-soft-text small mt-1">Akumulasi kerja hari ini</div>
          </div>
          <div class="attendance-detail-item">
            <small>Keterlambatan</small>
            <strong>{{ $todayAttendance->late_duration_label }}</strong>
            <div class="attendance-soft-text small mt-1">Dihitung otomatis sistem</div>
          </div>
          <div class="attendance-detail-item">
            <small>Lokasi Tervalidasi</small>
            <strong>{{ $validatedLocationLabel ?? '-' }}</strong>
            <div class="attendance-soft-text small mt-1">
              @if ($validatedLocationLabel)
                Area absensi yang cocok dengan koordinat browser.
              @else
                Belum ada lokasi master yang cocok atau dikonfigurasi.
              @endif
            </div>
          </div>
          <div class="attendance-detail-item">
            <small>Akurasi Browser</small>
            <strong>{{ $todayAttendance->check_in_accuracy ? number_format((float) $todayAttendance->check_in_accuracy, 0, ',', '.') . ' m' : '-' }}</strong>
            <div class="attendance-soft-text small mt-1">Dikirim otomatis saat proses absensi.</div>
          </div>
        </div>

        @if ($isLeaveSubmissionToday)
          <div class="attendance-inline-note mt-4">
            <small class="text-uppercase fw-semibold attendance-soft-text d-block mb-2">Detail Pengajuan</small>
            <div class="fw-semibold mb-1">Alasan</div>
            <p class="mb-0">{{ $todayAttendance->reason ?: 'Tidak ada keterangan tambahan.' }}</p>
          </div>
        @elseif ($todayAttendance->reason)
          <div class="attendance-inline-note mt-4">
            <small class="text-uppercase fw-semibold attendance-soft-text d-block mb-2">Catatan Tambahan</small>
            <p class="mb-0">{{ $todayAttendance->reason }}</p>
          </div>
        @endif
      @else
        <div class="attendance-empty-state">
          <h6 class="mb-2">Belum Ada Aktivitas Hari Ini</h6>
          <p class="attendance-soft-text mb-0">Begitu Anda melakukan check in atau mengirim pengajuan izin dan sakit, detailnya akan langsung muncul di panel ini.</p>
        </div>
      @endif
    </div>
  </div>

  <div class="col-xl-4 col-lg-5 attendance-hide-on-mobile">
    <div class="card attendance-recent-card h-100">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
          <h5 class="mb-1">Aktivitas Terbaru</h5>
          <small class="attendance-soft-text">3 entri absensi paling baru</small>
        </div>
        <a href="#riwayat-absensi" class="btn btn-sm btn-text-primary p-0">Lihat Riwayat</a>
      </div>

      <div class="attendance-recent-list">
        @forelse ($recentEntries as $attendance)
          <div class="attendance-recent-item">
            <div class="attendance-recent-icon bg-label-{{ $attendance->status_badge_class }} text-{{ $attendance->status_badge_class }}">
              <i class="ri {{ $statusIcons[$attendance->status] ?? 'ri-calendar-check-line' }}"></i>
            </div>
            <div>
              <div class="fw-semibold">{{ $attendance->date->locale('id')->translatedFormat('d M Y') }}</div>
              <div class="attendance-soft-text small">{{ $attendance->status_label }}</div>
            </div>
            <div class="attendance-recent-time">
              {{ $attendance->check_in_at?->format('H:i') ?? '-' }}
              <div class="attendance-soft-text small fw-normal">
                {{ $attendance->check_out_at?->format('H:i') ?? 'Belum pulang' }}
              </div>
            </div>
          </div>
        @empty
          <div class="attendance-empty-state text-center">
            <div class="avatar avatar-xl mb-3">
              <span class="avatar-initial rounded-4 bg-label-secondary">
                <i class="ri ri-time-line icon-28px"></i>
              </span>
            </div>
            <h6 class="mb-1">Belum Ada Riwayat</h6>
            <p class="attendance-soft-text mb-0">Aktivitas absensi terbaru akan muncul di sini setelah Anda mulai mencatat kehadiran.</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>

  <div class="col-12 attendance-hide-on-mobile">
    <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
      <div>
        <h3 class="attendance-section-title">Insight Bulanan</h3>
        <p class="attendance-soft-text mb-0">Ringkasan performa absensi Anda pada periode bulan berjalan.</p>
      </div>
      <a href="#riwayat-absensi" class="attendance-section-link">
        Lihat Riwayat <i class="ri ri-arrow-right-up-line ms-1"></i>
      </a>
    </div>
  </div>

  <div class="col-12 attendance-hide-on-mobile">
    <div class="row g-4">
      @foreach ($monthlyInsightCards as $card)
        <div class="col-md-6 col-xl">
          <div class="card attendance-kpi-card">
            <div class="attendance-kpi-icon {{ $card['iconClass'] }}">
              <i class="ri {{ $card['icon'] }} attendance-kpi-icon-glyph"></i>
            </div>
            <div class="attendance-kpi-label">{{ $card['label'] }}</div>
            <div class="attendance-kpi-value">
              <h3>{{ $card['value'] }}</h3>
              @if ($card['unit'])
                <span class="attendance-kpi-unit">{{ $card['unit'] }}</span>
              @endif
            </div>
            <p class="mb-0 small">{{ $card['description'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <div class="col-12" id="riwayat-absensi">
    <div class="card attendance-history-card">
      <div class="d-flex flex-column flex-xl-row justify-content-between gap-3 mb-4">
        <div>
          <h5 class="mb-1">Riwayat Absensi Lengkap</h5>
          <small class="attendance-soft-text">Gunakan filter untuk melihat pola kehadiran Anda berdasarkan bulan dan status.</small>
        </div>
        <form class="attendance-filter-shell d-flex flex-wrap align-items-end gap-3 m-0" method="GET">
          <div class="col-12 col-sm-auto">
            <label for="filter-month">Bulan</label>
            <input type="month" id="filter-month" name="month" value="{{ $selectedMonth }}" class="form-control" style="min-width: 180px;" />
          </div>
          <div class="col-12 col-sm-auto">
            <label for="filter-status">Status</label>
            <select id="filter-status" name="status" class="form-select" style="min-width: 180px;">
              <option value="">Semua Status</option>
              @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected($selectedStatus === $value)>{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary px-4">Terapkan</button>
          </div>
        </form>
      </div>

      <div class="table-responsive attendance-history-table-wrap d-none d-md-block">
        <table class="table table-hover align-middle attendance-history-table">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Status</th>
              <th>Check In</th>
              <th>Check Out</th>
              <th>Durasi</th>
              <th>Lokasi</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($attendances as $attendance)
              <tr>
                <td>
                  <div class="fw-medium">{{ $attendance->date->locale('id')->translatedFormat('d M Y') }}</div>
                  <small class="attendance-soft-text">{{ $attendance->date->locale('id')->translatedFormat('l') }}</small>
                </td>
                <td><span class="badge bg-label-{{ $attendance->status_badge_class }}">{{ $attendance->status_label }}</span></td>
                <td>{{ $attendance->check_in_at?->format('H:i') ?? '-' }}</td>
                <td>{{ $attendance->check_out_at?->format('H:i') ?? '-' }}</td>
                <td>{{ $resolveWorkDurationLabel($attendance) }}</td>
                <td>
                  <div class="fw-medium">{{ $attendance->attendanceLocation?->name ?? '-' }}</div>
                  @if ($attendance->check_in_distance_meters !== null)
                    <small class="attendance-soft-text">{{ $attendance->check_in_distance_meters }} m dari titik</small>
                  @endif
                </td>
                <td>
                  @if ($attendance->reason || $attendance->attachment_path)
                    @php
                      $detailModalId = 'attendance-detail-'.$attendance->id;
                      $attachmentUrl = $attendance->attachment_path
                        ? \Illuminate\Support\Facades\Storage::disk(config('attendance.attachment_disk'))->url($attendance->attachment_path)
                        : null;
                      $attachmentExtension = $attendance->attachment_path
                        ? strtolower(pathinfo($attendance->attachment_path, PATHINFO_EXTENSION))
                        : null;
                      $isImageAttachment = in_array($attachmentExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
                      $isPdfAttachment = $attachmentExtension === 'pdf';
                    @endphp
                    <button
                      type="button"
                      class="btn btn-sm btn-outline-primary attendance-detail-button"
                      data-bs-toggle="modal"
                      data-bs-target="#{{ $detailModalId }}"
                      aria-label="Lihat detail absensi">
                      <i class="ri ri-eye-line me-1"></i> Lihat
                    </button>

                    <div class="modal fade" id="{{ $detailModalId }}" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Detail Keterangan Absensi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <div class="mb-3">
                              <small class="text-body-secondary d-block mb-1">Tanggal</small>
                              <strong>{{ $attendance->date->locale('id')->translatedFormat('d M Y') }}</strong>
                            </div>
                            <div class="mb-3">
                              <small class="text-body-secondary d-block mb-1">Status</small>
                              <span class="badge bg-label-{{ $attendance->status_badge_class }}">{{ $attendance->status_label }}</span>
                            </div>
                            <div class="mb-0">
                              <small class="text-body-secondary d-block mb-1">Keterangan Lengkap</small>
                              <p class="mb-0">{{ $attendance->reason ?: 'Tidak ada keterangan tambahan.' }}</p>
                            </div>

                            <div class="mt-4">
                              <small class="text-body-secondary d-block mb-2">Lampiran</small>
                              @if ($attachmentUrl && $isImageAttachment)
                                <div class="attendance-preview-frame">
                                  <a href="{{ $attachmentUrl }}" target="_blank">
                                    <img src="{{ $attachmentUrl }}" alt="Lampiran absensi" class="attendance-preview-image">
                                  </a>
                                </div>
                              @elseif ($attachmentUrl && $isPdfAttachment)
                                <div class="attendance-preview-frame ratio ratio-4x3">
                                  <iframe src="{{ $attachmentUrl }}" title="Preview lampiran absensi"></iframe>
                                </div>
                              @elseif ($attachmentUrl)
                                <div class="attendance-preview-frame p-3 d-flex justify-content-between align-items-center gap-3">
                                  <div>
                                    <div class="fw-medium">Lampiran tersedia</div>
                                    <small class="text-body-secondary">Preview belum didukung untuk tipe file ini.</small>
                                  </div>
                                  <a href="{{ $attachmentUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    Buka File
                                  </a>
                                </div>
                              @else
                                <p class="mb-0 text-body-secondary">Tidak ada lampiran untuk absensi ini.</p>
                              @endif
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @else
                    <span class="attendance-soft-text">-</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center attendance-soft-text py-4">Belum ada riwayat absensi untuk filter ini.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="attendance-history-mobile-list d-md-none">
        @forelse ($attendances as $attendance)
          @php
            $detailModalId = 'attendance-detail-'.$attendance->id;
          @endphp

          <article class="card attendance-history-mobile-card">
            <div class="card-header border-bottom">
              <div class="d-flex align-items-start justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3 min-w-0">
                  <span class="attendance-history-mobile-avatar flex-shrink-0">{{ $attendance->date->format('d') }}</span>
                  <div class="min-w-0">
                    <h6 class="attendance-history-mobile-name text-truncate mb-0">{{ $attendance->date->locale('id')->translatedFormat('d M Y') }}</h6>
                    <div class="attendance-history-mobile-email text-truncate">{{ $attendance->date->locale('id')->translatedFormat('l') }}</div>
                  </div>
                </div>
                <span class="badge bg-label-{{ $attendance->status_badge_class }} rounded-pill flex-shrink-0">{{ $attendance->status_label }}</span>
              </div>
            </div>

            <div class="card-body">
              <div class="attendance-history-mobile-meta-list">
                <div class="attendance-history-mobile-meta-item">
                  <i class="ri ri-community-line"></i>
                  <span>{{ $internDivisionLabel }}</span>
                </div>
                <div class="attendance-history-mobile-meta-item">
                  <i class="ri ri-price-tag-3-line"></i>
                  <span>{{ $internTypeLabel }}</span>
                </div>
              </div>

              <div class="attendance-history-mobile-status">
                {{ $attendance->reason ?: 'Belum ada catatan absensi hari ini.' }}
              </div>

              <div class="attendance-history-mobile-standalone">
                <div class="attendance-history-mobile-meta-item">
                  <i class="ri ri-login-box-line"></i>
                  <span>Check In: {{ $attendance->check_in_at?->format('H:i') ?? '-' }}</span>
                </div>
                <div class="attendance-history-mobile-meta-item">
                  <i class="ri ri-logout-box-r-line"></i>
                  <span>Check Out: {{ $attendance->check_out_at?->format('H:i') ?? '-' }}</span>
                </div>
                <div class="attendance-history-mobile-meta-item">
                  <i class="ri ri-map-pin-line"></i>
                  <span>{{ $attendance->attendanceLocation?->name ?? '-' }}</span>
                </div>
              </div>

              <div class="attendance-history-mobile-stats">
                <div class="attendance-history-mobile-stat">
                  <small>Durasi Kerja</small>
                  <strong>{{ $resolveWorkDurationLabel($attendance) }}</strong>
                </div>
                <div class="attendance-history-mobile-stat">
                  <small>Keterlambatan</small>
                  <strong>{{ $attendance->late_duration_label }}</strong>
                </div>
                <div class="attendance-history-mobile-stat attendance-history-mobile-stat--full">
                  <small>Jarak Check-in</small>
                  <strong>{{ $attendance->check_in_distance_meters !== null ? $attendance->check_in_distance_meters.' m' : '-' }}</strong>
                </div>
              </div>

              @if ($attendance->reason || $attendance->attachment_path)
                <div class="attendance-history-mobile-actions">
                  <button
                    type="button"
                    class="btn btn-outline-primary btn-sm attendance-detail-button"
                    data-bs-toggle="modal"
                    data-bs-target="#{{ $detailModalId }}"
                    aria-label="Lihat detail absensi">
                    <i class="ri ri-eye-line me-1"></i> Lihat Detail
                  </button>
                </div>
              @endif
            </div>
          </article>
        @empty
          <div class="attendance-empty-state text-center">
            <div class="avatar avatar-xl mb-3">
              <span class="avatar-initial rounded-4 bg-label-secondary">
                <i class="ri ri-time-line icon-28px"></i>
              </span>
            </div>
            <h6 class="mb-1">Belum Ada Riwayat</h6>
            <p class="attendance-soft-text mb-0">Aktivitas absensi terbaru akan muncul di sini setelah Anda mulai mencatat kehadiran.</p>
          </div>
        @endforelse
      </div>

      @if ($attendances->hasPages())
        <div class="mt-4">
          {{ $attendances->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const clock = document.getElementById('attendanceLiveClock');
    const geolocationForms = document.querySelectorAll('[data-attendance-geolocation-form]');
    const geolocationFeedback = document.getElementById('attendanceGeolocationFeedback');

    const setFeedback = message => {
      if (!geolocationFeedback) {
        return;
      }

      if (!message) {
        geolocationFeedback.textContent = '';
        geolocationFeedback.classList.remove('is-visible');
        return;
      }

      geolocationFeedback.textContent = message;
      geolocationFeedback.classList.add('is-visible');
    };

    geolocationForms.forEach(form => {
      const submitButton = form.querySelector('button[type="submit"]');
      const originalLabel = submitButton ? submitButton.innerHTML : '';

      form.addEventListener('submit', function (event) {
        if (form.dataset.locationReady === 'true') {
          return;
        }

        event.preventDefault();
        setFeedback('');

        if (!navigator.geolocation) {
          setFeedback('Browser ini tidak mendukung pengambilan lokasi. Gunakan browser mobile yang mengizinkan GPS.');
          return;
        }

        if (submitButton) {
          submitButton.disabled = true;
          submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengambil Lokasi...';
        }

        navigator.geolocation.getCurrentPosition(
          position => {
            form.querySelector('[name="latitude"]').value = position.coords.latitude.toFixed(7);
            form.querySelector('[name="longitude"]').value = position.coords.longitude.toFixed(7);
            form.querySelector('[name="accuracy"]').value = position.coords.accuracy
              ? position.coords.accuracy.toFixed(2)
              : '';

            form.dataset.locationReady = 'true';
            form.submit();
          },
          error => {
            const message = error.code === error.PERMISSION_DENIED
              ? 'Izin lokasi ditolak. Aktifkan akses lokasi browser agar absensi bisa diproses.'
              : 'Lokasi gagal diambil dari browser. Pastikan GPS aktif lalu coba lagi.';

            setFeedback(message);

            if (submitButton) {
              submitButton.disabled = false;
              submitButton.innerHTML = originalLabel;
            }
          },
          {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 0
          }
        );
      });
    });

    if (!clock || !clock.dataset.now) {
      return;
    }

    let current = new Date(clock.dataset.now);

    const formatTime = value =>
      value.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
      });

    const tick = () => {
      clock.textContent = formatTime(current);
      current = new Date(current.getTime() + 1000);
    };

    tick();
    window.setInterval(tick, 1000);
  });
</script>
@endsection
