@extends('layouts/contentNavbarLayout')

@section('title', 'Monitoring Absensi Intern')

@section('page-style')
<style>
  .monitoring-mentor-page {
    --monitor-primary: #4338ca;
    --monitor-primary-soft: #eef0ff;
    --monitor-border: rgba(82, 88, 126, 0.14);
    --monitor-text-soft: #8d93ac;
  }

  .monitoring-mentor-page .card {
    border-radius: 1.5rem;
    border: 1px solid var(--monitor-border);
    box-shadow: 0 18px 45px rgba(31, 38, 69, 0.06);
  }

  .monitoring-header-card,
  .monitoring-table-card {
    background: #fff;
  }

  .monitoring-header-card {
    padding: 1.6rem;
  }

  .monitoring-title {
    font-size: 2rem;
    color: #1f2744;
    margin-bottom: 0.35rem;
  }

  .monitoring-soft-text {
    color: var(--monitor-text-soft);
  }

  .monitoring-filter-shell {
    border-radius: 1.2rem;
    padding: 1rem;
    border: 1px solid rgba(90, 96, 141, 0.12);
    background: linear-gradient(180deg, #fbfcff 0%, #f8f9ff 100%);
  }

  .monitoring-filter-shell label {
    display: block;
    margin-bottom: 0.4rem;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--monitor-text-soft);
  }

  .monitoring-filter-shell .form-control,
  .monitoring-filter-shell .form-select {
    min-height: 2.85rem;
    border-radius: 1rem;
  }

  .monitoring-summary-card {
    padding: 1.25rem 1.35rem;
    background: #fff;
    height: 100%;
  }

  .monitoring-summary-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.35rem 0.7rem;
    border-radius: 999px;
    font-size: 0.76rem;
    font-weight: 700;
  }

  .monitoring-summary-value {
    margin-top: 1rem;
    font-size: 2.2rem;
    line-height: 1;
    color: #161f39;
    font-weight: 800;
  }

  .monitoring-table-card {
    padding: 0;
    overflow: hidden;
  }

  .monitoring-table-card .table {
    margin-bottom: 0;
  }

  .monitoring-table-card thead th {
    font-size: 0.8rem;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: #4c5675;
    background: #f6f7fc;
    border-bottom: 0;
    padding-top: 1.1rem;
    padding-bottom: 1.1rem;
  }

  .monitoring-table-card tbody td {
    padding-top: 1.15rem;
    padding-bottom: 1.15rem;
    border-color: rgba(90, 96, 141, 0.1);
    vertical-align: middle;
  }

  .monitoring-avatar {
    width: 2.7rem;
    height: 2.7rem;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: #edefff;
    color: var(--monitor-primary);
    font-weight: 800;
  }

  .monitoring-category-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.55rem;
    border-radius: 0.65rem;
    background: #edf0ff;
    color: #56607f;
    font-size: 0.76rem;
    font-weight: 600;
  }

  .monitoring-attendance-total {
    font-size: 1.2rem;
    font-weight: 700;
    color: #171f39;
  }

  .monitoring-detail-btn {
    min-width: 7.75rem;
    border-radius: 0.95rem;
  }

  .monitoring-empty-state {
    padding: 2rem;
    text-align: center;
  }
</style>
@endsection

@section('content')
@php
  $monitorDate = now()->locale('id')->translatedFormat('l, d F Y');
  $selectedCategory = request('category');
  $workingDays = collect(\Carbon\CarbonPeriod::create(now()->startOfMonth(), now()->endOfMonth()))
      ->filter(fn ($date) => in_array($date->dayOfWeekIso, config('attendance.working_days', [1, 2, 3, 4, 5]), true))
      ->count();

  $resolveStatus = function ($attendance) {
      if (! $attendance) {
          return ['label' => 'Belum Absen', 'badge' => 'secondary', 'meta' => 'Belum ada catatan absensi hari ini.'];
      }

      return match ($attendance->status) {
          \App\Models\Attendance::STATUS_PRESENT => [
              'label' => $attendance->check_out_at ? 'Hadir' : 'Sedang Bekerja',
              'badge' => 'success',
              'meta' => $attendance->attendanceLocation?->name ?: 'Lokasi belum tercatat',
          ],
          \App\Models\Attendance::STATUS_LATE => [
              'label' => 'Terlambat',
              'badge' => 'warning',
              'meta' => $attendance->late_duration_label !== '-' ? 'Terlambat '.$attendance->late_duration_label : 'Melewati jam masuk',
          ],
          \App\Models\Attendance::STATUS_PERMISSION => [
              'label' => 'Izin',
              'badge' => 'info',
              'meta' => 'Pengajuan izin hari ini',
          ],
          \App\Models\Attendance::STATUS_SICK => [
              'label' => 'Sakit',
              'badge' => 'danger',
              'meta' => 'Pengajuan sakit hari ini',
          ],
          default => [
              'label' => 'Belum Absen',
              'badge' => 'secondary',
              'meta' => 'Belum ada catatan absensi hari ini.',
          ],
      };
  };

  $summaryDescriptions = [
      'hadir' => 'Intern bimbingan yang absennya terekam tepat waktu hari ini.',
      'terlambat' => 'Intern bimbingan yang check in melewati jam masuk.',
      'izin' => 'Intern bimbingan dengan pengajuan izin hari ini.',
      'sakit' => 'Intern bimbingan dengan pengajuan sakit hari ini.',
      'belum_absen' => 'Intern bimbingan yang belum memiliki catatan absensi hari ini.',
  ];
@endphp

<div class="monitoring-mentor-page row g-6">
  <div class="col-12">
    @include('partials.app-breadcrumb', [
      'items' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.mentor')],
        ['label' => 'Absensi Intern', 'current' => true],
      ],
    ])
  </div>

  <div class="col-12">
    <div class="card monitoring-header-card">
      <div class="d-flex flex-column flex-xl-row justify-content-between gap-4">
        <div>
          <h3 class="monitoring-title">Monitoring Absensi Intern Bimbingan</h3>
          <p class="monitoring-soft-text mb-0">Pantau status absensi harian intern di divisi Anda dan lanjutkan ke detail riwayat per orang saat diperlukan.</p>
          <small class="monitoring-soft-text d-block mt-2">Snapshot hari ini: {{ $monitorDate }}</small>
        </div>

        <form class="monitoring-filter-shell d-flex flex-wrap align-items-end gap-3 m-0" method="GET">
          <div>
            <label for="monitor-search">Cari Nama Intern</label>
            <input
              id="monitor-search"
              type="text"
              name="search"
              value="{{ request('search') }}"
              class="form-control"
              placeholder="Nama intern..." />
          </div>
          <div>
            <label for="monitor-category">Kategori</label>
            <select id="monitor-category" name="category" class="form-select">
              <option value="">Semua Kategori</option>
              @foreach ($categoryOptions as $value => $label)
                <option value="{{ $value }}" @selected($selectedCategory === $value)>{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">Terapkan</button>
            <a href="{{ route('mentor.attendances.index') }}" class="btn btn-outline-secondary px-4">Reset</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  @foreach ($summary as $item)
    <div class="col-md-6 col-xl">
      <div class="card monitoring-summary-card">
        <span class="monitoring-summary-badge bg-label-{{ $item['badge'] }} text-{{ $item['badge'] }}">
          <span class="dot"></span>{{ $item['label'] }}
        </span>
        <div class="monitoring-summary-value">{{ $item['count'] }}</div>
        <p class="monitoring-soft-text small mb-0 mt-2">{{ $summaryDescriptions[$item['key']] ?? 'Ringkasan status intern hari ini.' }}</p>
      </div>
    </div>
  @endforeach

  <div class="col-12">
    <div class="card monitoring-table-card">
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Intern</th>
              <th>Divisi</th>
              <th>Kategori</th>
              <th>Status Hari Ini</th>
              <th>Check In</th>
              <th>Check Out</th>
              <th>Total Kehadiran</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($interns as $intern)
              @php
                $todayAttendance = $intern->attendances->first();
                $rowStatus = $resolveStatus($todayAttendance);
                $initials = collect(explode(' ', trim($intern->name)))
                  ->filter()
                  ->take(2)
                  ->map(fn ($part) => mb_substr($part, 0, 1))
                  ->implode('');
                $categoryLabel = ucfirst($intern->type ?? 'intern');
              @endphp
              <tr>
                <td>
                  <div class="d-flex align-items-center gap-3">
                    <span class="monitoring-avatar">{{ strtoupper($initials ?: 'IN') }}</span>
                    <div>
                      <div class="fw-semibold">{{ $intern->name }}</div>
                      <small class="monitoring-soft-text">{{ $intern->user?->email ?? $intern->email ?? '-' }}</small>
                    </div>
                  </div>
                </td>
                <td>{{ $intern->division->name ?? '-' }}</td>
                <td><span class="monitoring-category-badge">{{ $categoryLabel }}</span></td>
                <td>
                  <span class="badge rounded-pill bg-label-{{ $rowStatus['badge'] }}">{{ $rowStatus['label'] }}</span>
                  <div class="small monitoring-soft-text mt-1">{{ $rowStatus['meta'] }}</div>
                </td>
                <td>{{ $todayAttendance?->check_in_at?->format('H:i') ?? '-' }}</td>
                <td>{{ $todayAttendance?->check_out_at?->format('H:i') ?? '-' }}</td>
                <td>
                  <div class="monitoring-attendance-total">{{ $intern->attendance_records_count }}/{{ $workingDays }} Hari</div>
                  <small class="monitoring-soft-text">Ringkasan bulan berjalan</small>
                </td>
                <td>
                  <a href="{{ route('mentor.attendances.show', $intern) }}" class="btn btn-primary btn-sm monitoring-detail-btn">
                    Lihat Detail
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8">
                  <div class="monitoring-empty-state">
                    <h6 class="mb-2">Belum Ada Intern yang Sesuai</h6>
                    <p class="monitoring-soft-text mb-0">Coba ubah pencarian atau kategori untuk melihat data intern lainnya.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if ($interns->hasPages())
        <div class="px-4 py-3 border-top d-flex justify-content-end">
          {{ $interns->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
