@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Absensi Intern')

@section('page-style')
<style>
  .admin-attendance-detail {
    --detail-primary: #4338ca;
    --detail-border: rgba(84, 90, 130, 0.14);
    --detail-soft: #8d93ac;
  }

  .admin-attendance-detail .card {
    border-radius: 1.5rem;
    border: 1px solid var(--detail-border);
    box-shadow: 0 18px 45px rgba(31, 38, 69, 0.06);
  }

  .admin-detail-hero {
    overflow: hidden;
    border: 0;
    color: #fff;
    background:
      radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), transparent 26%),
      radial-gradient(circle at bottom left, rgba(125, 115, 255, 0.24), transparent 32%),
      linear-gradient(135deg, #2f27c7 0%, #4f46e5 52%, #625cf2 100%);
  }

  .admin-detail-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.5rem 0.85rem;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.14);
    border: 1px solid rgba(255, 255, 255, 0.12);
  }

  .admin-detail-badge-dot {
    width: 0.55rem;
    height: 0.55rem;
    border-radius: 999px;
    background: #6ee7b7;
  }

  .admin-detail-soft {
    color: var(--detail-soft);
  }

  .admin-detail-box {
    border-radius: 1.15rem;
    border: 1px solid rgba(90, 96, 141, 0.12);
    padding: 1rem 1.05rem;
    background: #fff;
    height: 100%;
  }

  .admin-detail-box small {
    display: block;
    margin-bottom: 0.25rem;
    color: var(--detail-soft);
  }

  .admin-detail-box strong {
    color: #202844;
    font-size: 1.15rem;
  }

  .admin-detail-kpi-card {
    padding: 1.35rem;
    height: 100%;
  }

  .admin-detail-kpi-card h3 {
    margin: 0.8rem 0 0;
    font-size: 2.2rem;
    color: #161f39;
  }

  .admin-detail-kpi-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
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

<div class="admin-attendance-detail row g-6">
  <div class="col-12">
    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
      <div>
        <h4 class="mb-1">Detail Riwayat Absensi Intern</h4>
        <p class="admin-detail-soft mb-0">Pantau riwayat kehadiran, lokasi tervalidasi, dan catatan absensi untuk {{ $intern->name }}.</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.interns.show', $intern) }}" class="btn btn-outline-primary">
          <i class="ri ri-user-line me-1"></i> Profil Intern
        </a>
        <a href="{{ route('admin.attendances.index') }}" class="btn btn-outline-secondary">
          <i class="ri ri-arrow-left-line me-1"></i> Kembali
        </a>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card admin-detail-hero">
      <div class="card-body p-4 p-xl-5">
        <div class="d-flex flex-wrap gap-2 mb-4">
          <span class="admin-detail-badge">
            <span class="admin-detail-badge-dot"></span>
            {{ ucfirst($intern->type ?? 'Intern') }}
          </span>
          <span class="admin-detail-badge">
            <span class="admin-detail-badge-dot"></span>
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
          <small class="admin-detail-soft">{{ $todayLabel }}</small>
        </div>
        <span class="badge rounded-pill bg-label-{{ $todayAttendance?->status_badge_class ?? 'secondary' }}">{{ $todayStep }}</span>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6 col-xl-4">
            <div class="admin-detail-box">
              <small>Check In</small>
              <strong>{{ $todayAttendance?->check_in_at?->format('H:i') ?? '-' }}</strong>
            </div>
          </div>
          <div class="col-md-6 col-xl-4">
            <div class="admin-detail-box">
              <small>Check Out</small>
              <strong>{{ $todayAttendance?->check_out_at?->format('H:i') ?? '-' }}</strong>
            </div>
          </div>
          <div class="col-md-6 col-xl-4">
            <div class="admin-detail-box">
              <small>Keterlambatan</small>
              <strong>{{ $todayAttendance?->late_duration_label ?? '-' }}</strong>
            </div>
          </div>
          <div class="col-md-6 col-xl-4">
            <div class="admin-detail-box">
              <small>Durasi Kerja</small>
              <strong>{{ $formatMinutes($currentWorkMinutes) }}</strong>
            </div>
          </div>
          <div class="col-md-6 col-xl-4">
            <div class="admin-detail-box">
              <small>Jarak Check In</small>
              <strong>{{ $todayAttendance?->check_in_distance_meters !== null ? $todayAttendance->check_in_distance_meters.' m' : '-' }}</strong>
            </div>
          </div>
          <div class="col-md-6 col-xl-4">
            <div class="admin-detail-box">
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
        <small class="admin-detail-soft">4 catatan absensi paling baru</small>
      </div>
      <div class="card-body d-flex flex-column gap-3">
        @forelse ($recentEntries as $attendance)
          <div class="admin-detail-box">
            <small>{{ $attendance->date->locale('id')->translatedFormat('d M Y') }}</small>
            <div class="fw-semibold">{{ $attendance->status_label }}</div>
            <div class="admin-detail-soft small mt-1">
              {{ $attendance->attendanceLocation?->name ?? 'Tanpa lokasi tervalidasi' }}
            </div>
          </div>
        @empty
          <div class="admin-detail-box">
            <div class="fw-semibold">Belum ada riwayat absensi.</div>
          </div>
        @endforelse
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="row g-4">
      @foreach ($attendanceSummary['attendanceStatusCounts'] as $item)
        <div class="col-md-6 col-xl">
          <div class="card admin-detail-kpi-card">
            <span class="admin-detail-kpi-icon bg-label-{{ $item['badge'] }} text-{{ $item['badge'] }}">
              <i class="ri ri-bar-chart-grouped-line"></i>
            </span>
            <h3>{{ $item['count'] }}</h3>
            <p class="mb-0 admin-detail-soft">{{ $item['label'] }}</p>
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
          <small class="admin-detail-soft">Pantau semua catatan absensi intern ini berdasarkan bulan dan status.</small>
        </div>
        <form class="d-flex flex-wrap gap-3" method="GET">
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
        <div class="table-responsive">
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
                    <small class="admin-detail-soft">{{ $attendance->date->locale('id')->translatedFormat('l') }}</small>
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
                  <td colspan="7" class="text-center py-4 admin-detail-soft">Belum ada riwayat absensi untuk filter ini.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @if ($attendances->hasPages())
        <div class="card-footer bg-white border-top-0">
          {{ $attendances->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
