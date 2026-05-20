@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Absensi Intern')

@section('page-style')
<style>
  .mentor-attendance-detail {
    --detail-primary: #5b6ef0;
    --detail-primary-soft: rgba(91, 110, 240, 0.14);
    --detail-border: var(--bs-border-color);
    --detail-soft: var(--bs-secondary-color);
    --detail-heading: var(--bs-heading-color);
    --detail-surface: rgba(var(--bs-body-color-rgb), 0.03);
    --detail-card-bg: var(--bs-card-bg);
  }

  html[data-bs-theme="dark"] .mentor-attendance-detail {
    --detail-border: rgba(255, 255, 255, 0.08);
    --detail-soft: #9aa3c2;
    --detail-heading: #f4f7ff;
    --detail-surface: rgba(255, 255, 255, 0.035);
    --detail-card-bg: #1a2033;
    --detail-primary-soft: rgba(109, 124, 255, 0.18);
  }

  .mentor-attendance-detail .card {
    border-radius: 1.35rem;
    border: 1px solid var(--detail-border);
    box-shadow: 0 16px 34px rgba(31, 38, 69, 0.06);
  }

  .mentor-detail-hero {
    overflow: hidden;
    border: 0;
    color: #fff;
    background:
      radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), transparent 26%),
      radial-gradient(circle at bottom left, rgba(125, 115, 255, 0.24), transparent 32%),
      linear-gradient(135deg, #2f27c7 0%, #4f46e5 52%, #625cf2 100%);
  }

  .mentor-detail-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.5rem 0.85rem;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.14);
    border: 1px solid rgba(255, 255, 255, 0.12);
  }

  .mentor-detail-badge-dot {
    width: 0.55rem;
    height: 0.55rem;
    border-radius: 999px;
    background: #6ee7b7;
  }

  .mentor-detail-soft {
    color: var(--detail-soft);
  }

  .mentor-detail-box {
    border-radius: 1.15rem;
    border: 1px solid var(--detail-border);
    padding: 0.95rem 1rem;
    background: var(--detail-card-bg);
    height: 100%;
  }

  .mentor-detail-box small {
    display: block;
    margin-bottom: 0.25rem;
    color: var(--detail-soft);
  }

  .mentor-detail-box strong {
    color: var(--detail-heading);
    font-size: 1.15rem;
  }

  .mentor-detail-kpi-card {
    padding: 1.2rem;
    height: 100%;
    background: var(--detail-card-bg);
  }

  .mentor-detail-kpi-card h3 {
    margin: 0.8rem 0 0;
    font-size: 2rem;
    color: var(--detail-heading);
  }

  .mentor-detail-kpi-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
  }

  .mentor-attendance-detail .mentor-detail-filter-shell {
    border-radius: 1.15rem;
    border: 1px solid var(--detail-border);
    background: var(--detail-surface);
    padding: 0.9rem;
  }

  .mentor-attendance-detail .mentor-detail-filter-shell .form-control,
  .mentor-attendance-detail .mentor-detail-filter-shell .form-select {
    border-radius: 0.95rem;
    min-height: 2.75rem;
  }

  .mentor-attendance-detail .mentor-detail-filter-shell > .form-control,
  .mentor-attendance-detail .mentor-detail-filter-shell > .form-select,
  .mentor-attendance-detail .mentor-detail-filter-shell > .btn {
    flex: 1 1 190px;
    min-width: 0;
  }

  .mentor-attendance-table-mobile {
    display: grid;
    gap: 0.8rem;
  }

  .mentor-attendance-mobile-card {
    border-radius: 1.15rem;
    border: 1px solid var(--detail-border);
    background: var(--detail-card-bg);
    padding: 1rem;
    box-shadow: 0 12px 28px rgba(31, 38, 69, 0.04);
  }

  .mentor-attendance-mobile-meta {
    display: grid;
    gap: 0.55rem;
  }

  .mentor-attendance-mobile-row {
    display: flex;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0.6rem 0.8rem;
    border-radius: 0.95rem;
    background: var(--detail-surface);
  }

  .mentor-attendance-mobile-row span {
    color: var(--detail-soft);
    font-size: 0.8rem;
  }

  .mentor-attendance-mobile-row strong {
    color: var(--detail-heading);
    font-size: 0.95rem;
  }

  .mentor-detail-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.42rem 0.7rem;
    border-radius: 999px;
    background: var(--detail-surface);
    color: var(--detail-heading);
    font-weight: 600;
    font-size: 0.8rem;
  }

  @media (max-width: 991.98px) {
    .mentor-attendance-detail .mentor-detail-hero .card-body {
      padding: 1.15rem;
    }

    .mentor-attendance-detail .mentor-detail-kpi-card {
      padding: 1rem;
    }

    .mentor-attendance-detail .mentor-detail-kpi-card h3 {
      font-size: 1.75rem;
    }

    .mentor-attendance-detail .mentor-detail-box {
      padding: 0.85rem 0.9rem;
    }

    .mentor-attendance-detail .mentor-detail-filter-shell {
      padding: 0.8rem;
    }
  }

  @media (max-width: 575.98px) {
    .mentor-attendance-detail .card {
      border-radius: 1.1rem;
    }

    .mentor-attendance-detail .mentor-detail-hero .card-body {
      padding: 1rem;
    }

    .mentor-attendance-detail h4,
    .mentor-attendance-detail h2 {
      word-break: break-word;
    }

    .mentor-attendance-detail .mentor-detail-box strong {
      font-size: 1rem;
    }

    .mentor-attendance-detail .mentor-detail-kpi-card h3 {
      font-size: 1.55rem;
    }

    .mentor-attendance-detail .mentor-detail-filter-shell > .form-control,
    .mentor-attendance-detail .mentor-detail-filter-shell > .form-select,
    .mentor-attendance-detail .mentor-detail-filter-shell > .btn {
      flex-basis: 100%;
      width: 100%;
    }

    .mentor-attendance-detail .mentor-detail-filter-shell > .btn {
      justify-content: center;
    }
  }
</style>
@endsection

@section('content')
@php
  $now = now();
  $todayLabel = $now->locale('id')->translatedFormat('l, d F Y');
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

  $todayStep = match (true) {
      ! $todayAttendance => 'Belum Absen',
      $todayAttendance->status === \App\Models\Attendance::STATUS_PERMISSION => 'Izin Hari Ini',
      $todayAttendance->status === \App\Models\Attendance::STATUS_SICK => 'Sakit Hari Ini',
      $todayAttendance->status === \App\Models\Attendance::STATUS_LATE => 'Terlambat',
      $todayAttendance->check_in_at && ! $todayAttendance->check_out_at => 'Sedang Bekerja',
      default => $todayAttendance->status_label,
  };

  $recentEntries = $attendanceSummary['recentAttendances']->take(4);
@endphp

<div class="mentor-attendance-detail row g-6">
  <div class="col-12">
    @include('partials.app-breadcrumb', [
      'items' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.mentor')],
        ['label' => 'Absensi Intern', 'url' => route('mentor.attendances.index')],
        ['label' => 'Detail', 'current' => true],
      ],
    ])
  </div>

  <div class="col-12">
    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
      <div>
        <h4 class="mb-1">Detail Riwayat Absensi Intern</h4>
        <p class="mentor-detail-soft mb-0">Pantau riwayat kehadiran, lokasi tervalidasi, dan catatan absensi untuk {{ $intern->name }}.</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('mentor.attendances.index') }}" class="btn btn-outline-secondary">
          <i class="ri ri-arrow-left-line me-1"></i> Kembali
        </a>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card mentor-detail-hero">
      <div class="card-body p-4 p-xl-5">
        <div class="d-flex flex-wrap gap-2 mb-4">
          <span class="mentor-detail-badge">
            <span class="mentor-detail-badge-dot"></span>
            {{ ucfirst($intern->type ?? 'Intern') }}
          </span>
          <span class="mentor-detail-badge">
            <span class="mentor-detail-badge-dot"></span>
            {{ $intern->division->name ?? 'Tanpa Divisi' }}
          </span>
        </div>

        <div class="row g-4 align-items-end">
          <div class="col-lg-8">
            <h2 class="text-white mb-2">{{ $intern->name }}</h2>
            <div class="fs-5 text-white-50">{{ $intern->user?->email ?? $intern->email ?? '-' }}</div>
            <div class="fs-4 fw-semibold mt-4">{{ $todayStep }}</div>
            <div class="text-white-50 mt-2">Snapshot hari ini: {{ $todayLabel }}</div>
          </div>
          <div class="col-lg-4">
            <div class="card bg-white bg-opacity-10 border-0 h-100">
              <div class="card-body">
                <div class="text-white-50 small mb-1">Lokasi tervalidasi hari ini</div>
                <div class="fs-5 fw-semibold text-white">{{ $todayAttendance?->attendanceLocation?->name ?? 'Belum ada lokasi tervalidasi' }}</div>
                <div class="text-white-50 small mt-2">
                  @if ($todayAttendance?->check_in_distance_meters !== null)
                    {{ $todayAttendance->check_in_distance_meters }} m dari titik • Accuracy {{ $todayAttendance->check_in_accuracy ? number_format((float) $todayAttendance->check_in_accuracy, 0, ',', '.') . ' m' : '-' }}
                  @else
                    Detail jarak dan akurasi akan muncul setelah absensi dilakukan.
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-1">Status Hari Ini</h5>
          <small class="mentor-detail-soft">{{ $todayLabel }}</small>
        </div>
        <span class="badge rounded-pill bg-label-{{ $todayAttendance?->status_badge_class ?? 'secondary' }}">{{ $todayStep }}</span>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6 col-xl-4">
            <div class="mentor-detail-box">
              <small>Check In</small>
              <strong>{{ $todayAttendance?->check_in_at?->format('H:i') ?? '-' }}</strong>
            </div>
          </div>
          <div class="col-md-6 col-xl-4">
            <div class="mentor-detail-box">
              <small>Check Out</small>
              <strong>{{ $todayAttendance?->check_out_at?->format('H:i') ?? '-' }}</strong>
            </div>
          </div>
          <div class="col-md-6 col-xl-4">
            <div class="mentor-detail-box">
              <small>Keterlambatan</small>
              <strong>{{ $todayAttendance?->late_duration_label ?? '-' }}</strong>
            </div>
          </div>
          <div class="col-md-6 col-xl-4">
            <div class="mentor-detail-box">
              <small>Durasi Kerja</small>
              <strong>{{ $formatMinutes($currentWorkMinutes) }}</strong>
            </div>
          </div>
          <div class="col-md-6 col-xl-4">
            <div class="mentor-detail-box">
              <small>Jarak Check In</small>
              <strong>{{ $todayAttendance?->check_in_distance_meters !== null ? $todayAttendance->check_in_distance_meters.' m' : '-' }}</strong>
            </div>
          </div>
          <div class="col-md-6 col-xl-4">
            <div class="mentor-detail-box">
              <small>Akurasi Browser</small>
              <strong>{{ $todayAttendance?->check_in_accuracy ? number_format((float) $todayAttendance->check_in_accuracy, 0, ',', '.') . ' m' : '-' }}</strong>
            </div>
          </div>
        </div>

        @if ($todayAttendance?->reason)
          <div class="alert alert-light border mt-4 mb-0">
            <div class="fw-semibold mb-1">Keterangan Hari Ini</div>
            <div>{{ $todayAttendance->reason }}</div>
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="mb-1">Aktivitas Terbaru</h5>
        <small class="mentor-detail-soft">4 catatan absensi paling baru</small>
      </div>
      <div class="card-body d-flex flex-column gap-3">
        @forelse ($recentEntries as $attendance)
          <div class="mentor-detail-box">
            <small>{{ $attendance->date->locale('id')->translatedFormat('d M Y') }}</small>
            <div class="fw-semibold">{{ $attendance->status_label }}</div>
            <div class="mentor-detail-soft small mt-1">
              {{ $attendance->attendanceLocation?->name ?? 'Tanpa lokasi tervalidasi' }}
            </div>
          </div>
        @empty
          <div class="mentor-detail-box">
            <div class="fw-semibold">Belum ada riwayat absensi.</div>
          </div>
        @endforelse
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="row g-4">
      @foreach ($attendanceSummary['attendanceStatusCounts'] as $item)
        <div class="col-6 col-xl">
          <div class="card mentor-detail-kpi-card">
            <span class="mentor-detail-kpi-icon bg-label-{{ $item['badge'] }} text-{{ $item['badge'] }}">
              <i class="ri ri-bar-chart-grouped-line"></i>
            </span>
            <h3>{{ $item['count'] }}</h3>
            <p class="mb-0 mentor-detail-soft">{{ $item['label'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex flex-column flex-xl-row justify-content-between gap-3">
        <div>
          <h5 class="mb-1">Riwayat Absensi Lengkap</h5>
          <small class="mentor-detail-soft">Pantau semua catatan absensi intern ini berdasarkan bulan dan status.</small>
        </div>
        <form class="mentor-detail-filter-shell d-flex flex-wrap gap-3 m-0" method="GET">
          <input type="month" name="month" value="{{ $selectedMonth }}" class="form-control" style="min-width: 190px;" />
          <select name="status" class="form-select" style="min-width: 180px;">
            <option value="">Semua Status</option>
            @foreach ($statusOptions as $value => $label)
              <option value="{{ $value }}" @selected($selectedStatus === $value)>{{ $label }}</option>
            @endforeach
          </select>
          <button type="submit" class="btn btn-primary px-4">Terapkan</button>
        </form>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive d-none d-lg-block">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="ps-4">Tanggal</th>
                <th>Status</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Lokasi</th>
                <th>Jarak</th>
                <th>Keterangan</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($attendances as $attendance)
                <tr>
                  <td class="ps-4">
                    <div class="fw-medium">{{ $attendance->date->locale('id')->translatedFormat('d M Y') }}</div>
                    <small class="mentor-detail-soft">{{ $attendance->date->locale('id')->translatedFormat('l') }}</small>
                  </td>
                  <td><span class="badge bg-label-{{ $attendance->status_badge_class }}">{{ $attendance->status_label }}</span></td>
                  <td>{{ $attendance->check_in_at?->format('H:i') ?? '-' }}</td>
                  <td>{{ $attendance->check_out_at?->format('H:i') ?? '-' }}</td>
                  <td>{{ $attendance->attendanceLocation?->name ?? '-' }}</td>
                  <td>{{ $attendance->check_in_distance_meters !== null ? $attendance->check_in_distance_meters.' m' : '-' }}</td>
                  <td>{{ $attendance->reason ?: '-' }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-4 mentor-detail-soft">Belum ada riwayat absensi untuk filter ini.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mentor-attendance-table-mobile d-lg-none p-3">
          @forelse ($attendances as $attendance)
            <div class="mentor-attendance-mobile-card">
              <div class="d-flex justify-content-between align-items-start gap-3">
                <div>
                  <div class="fw-semibold">{{ $attendance->date->locale('id')->translatedFormat('d M Y') }}</div>
                  <small class="mentor-detail-soft">{{ $attendance->date->locale('id')->translatedFormat('l') }}</small>
                </div>
                <span class="badge bg-label-{{ $attendance->status_badge_class }}">{{ $attendance->status_label }}</span>
              </div>

              <div class="d-flex flex-wrap gap-2 mt-3">
                <span class="mentor-detail-chip">In: {{ $attendance->check_in_at?->format('H:i') ?? '-' }}</span>
                <span class="mentor-detail-chip">Out: {{ $attendance->check_out_at?->format('H:i') ?? '-' }}</span>
                <span class="mentor-detail-chip">{{ $attendance->check_in_distance_meters !== null ? $attendance->check_in_distance_meters.' m' : '-' }}</span>
              </div>

              <div class="mt-3">
                <div class="mentor-detail-soft small mb-1">Lokasi</div>
                <div class="fw-medium">{{ $attendance->attendanceLocation?->name ?? '-' }}</div>
              </div>

              <div class="mt-3">
                <div class="mentor-detail-soft small mb-1">Keterangan</div>
                <div>{{ $attendance->reason ?: '-' }}</div>
              </div>
            </div>
          @empty
            <div class="mentor-attendance-mobile-card text-center">
              Belum ada riwayat absensi untuk filter ini.
            </div>
          @endforelse
        </div>
      </div>
      @if ($attendances->hasPages())
        <div class="card-footer bg-transparent border-top-0">
          {{ $attendances->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
