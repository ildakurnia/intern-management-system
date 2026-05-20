@extends('layouts/contentNavbarLayout')

@section('title', 'Monitoring Absensi Intern')

@section('page-style')
<style>
  .monitoring-mentor-page {
    --monitor-card-bg: var(--bs-card-bg);
    --monitor-card-border: var(--bs-border-color);
    --monitor-soft-text: var(--bs-secondary-color);
    --monitor-heading: var(--bs-heading-color);
    --monitor-surface: rgba(var(--bs-body-color-rgb), 0.03);
    --monitor-primary: #5b6ef0;
    --monitor-primary-soft: rgba(91, 110, 240, 0.12);
  }

  html[data-bs-theme="dark"] .monitoring-mentor-page {
    --monitor-card-bg: #1a2033;
    --monitor-card-border: rgba(255, 255, 255, 0.08);
    --monitor-soft-text: #9aa3c2;
    --monitor-heading: #f4f7ff;
    --monitor-surface: rgba(255, 255, 255, 0.035);
    --monitor-primary-soft: rgba(109, 124, 255, 0.18);
  }

  .monitoring-mentor-page .card {
    border-radius: 1.35rem;
    border: 1px solid var(--monitor-card-border);
    box-shadow: 0 16px 34px rgba(31, 38, 69, 0.06);
  }

  .monitoring-header-card,
  .monitoring-table-card,
  .monitoring-mobile-card,
  .monitoring-summary-card {
    background: var(--monitor-card-bg);
  }

  .monitoring-header-card {
    padding: 1.45rem;
  }

  .monitoring-title {
    font-size: clamp(1.55rem, 2vw, 2rem);
    color: var(--monitor-heading);
    margin-bottom: 0.35rem;
  }

  .monitoring-soft-text {
    color: var(--monitor-soft-text);
  }

  .monitoring-filter-shell {
    border-radius: 1.1rem;
    padding: 1rem;
    border: 1px solid var(--monitor-card-border);
    background: var(--monitor-surface);
  }

  .monitoring-filter-shell label {
    display: block;
    margin-bottom: 0.4rem;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--monitor-soft-text);
  }

  .monitoring-filter-shell .form-control,
  .monitoring-filter-shell .form-select {
    min-height: 2.8rem;
    border-radius: 0.95rem;
  }

  .monitoring-filter-shell > div {
    min-width: 0;
    flex: 1 1 210px;
  }

  .monitoring-filter-shell .d-flex.gap-2 {
    flex: 1 1 auto;
  }

  .monitoring-summary-card {
    padding: 1.1rem 1.15rem;
    height: 100%;
  }

  .monitoring-summary-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.34rem 0.7rem;
    border-radius: 999px;
    font-size: 0.76rem;
    font-weight: 700;
  }

  .monitoring-summary-value {
    margin-top: 0.95rem;
    font-size: 2.05rem;
    line-height: 1;
    color: var(--monitor-heading);
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
    color: var(--monitor-soft-text);
    background: var(--monitor-surface);
    border-bottom: 0;
    padding-top: 1rem;
    padding-bottom: 1rem;
  }

  .monitoring-table-card tbody td {
    padding-top: 1rem;
    padding-bottom: 1rem;
    border-color: var(--monitor-card-border);
    vertical-align: middle;
  }

  .monitoring-avatar {
    width: 2.65rem;
    height: 2.65rem;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: var(--monitor-primary-soft);
    color: var(--monitor-primary);
    font-weight: 800;
  }

  .monitoring-category-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.6rem;
    border-radius: 0.7rem;
    background: var(--monitor-primary-soft);
    color: var(--monitor-heading);
    font-size: 0.76rem;
    font-weight: 600;
  }

  .monitoring-attendance-total {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--monitor-heading);
  }

  .monitoring-detail-btn {
    min-width: 7.4rem;
    border-radius: 0.95rem;
  }

  .monitoring-mobile-list {
    display: grid;
    gap: 0.85rem;
  }

  .monitoring-mobile-card {
    padding: 1rem;
    border-radius: 1.15rem;
    border: 1px solid var(--monitor-card-border);
    box-shadow: 0 12px 28px rgba(31, 38, 69, 0.04);
    position: relative;
    overflow: hidden;
  }

  .monitoring-mobile-card::before {
    content: '';
    position: absolute;
    inset: 0 0 auto 0;
    height: 3px;
    background: linear-gradient(90deg, var(--monitor-primary), rgba(91, 110, 240, 0.35));
  }

  .monitoring-mobile-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.9rem;
  }

  .monitoring-mobile-kicker {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    margin-bottom: 0.28rem;
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--monitor-soft-text);
  }

  .monitoring-mobile-name {
    font-size: 1rem;
    font-weight: 700;
    line-height: 1.25;
    color: var(--monitor-heading);
  }

  .monitoring-mobile-email {
    font-size: 0.8rem;
  }

  .monitoring-mobile-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.45rem;
    margin-top: 0.8rem;
  }

  .monitoring-mobile-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.28rem 0.62rem;
    border-radius: 999px;
    background: var(--monitor-primary-soft);
    color: var(--monitor-heading);
    font-size: 0.74rem;
    font-weight: 600;
  }

  .monitoring-mobile-note {
    margin-top: 0.95rem;
    padding: 0.8rem 0.9rem;
    border-radius: 1rem;
    border: 1px solid rgba(91, 110, 240, 0.12);
    background: linear-gradient(180deg, rgba(91, 110, 240, 0.07), rgba(91, 110, 240, 0.03));
  }

  .monitoring-mobile-note-title {
    margin-bottom: 0.2rem;
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--monitor-soft-text);
  }

  .monitoring-mobile-note-text {
    color: var(--monitor-heading);
    font-size: 0.92rem;
    line-height: 1.45;
  }

  .monitoring-mobile-stat-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.7rem;
    margin-top: 0.95rem;
  }

  .monitoring-mobile-stat {
    padding: 0.82rem 0.9rem;
    border-radius: 1rem;
    border: 1px solid var(--monitor-card-border);
    background: #ffffff;
  }

  .monitoring-mobile-stat--wide {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
  }

  .monitoring-mobile-stat-label {
    display: block;
    margin-bottom: 0.2rem;
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--monitor-soft-text);
  }

  .monitoring-mobile-stat-value {
    color: var(--monitor-heading);
    font-size: 0.95rem;
    font-weight: 700;
    line-height: 1.2;
  }

  .monitoring-mobile-meta {
    display: grid;
    gap: 0.5rem;
  }

  .monitoring-mobile-meta-row {
    display: flex;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0.62rem 0.8rem;
    border-radius: 0.95rem;
    background: var(--monitor-surface);
  }

  .monitoring-mobile-meta-row span {
    color: var(--monitor-soft-text);
    font-size: 0.8rem;
  }

  .monitoring-mobile-meta-row strong {
    color: var(--monitor-heading);
    font-size: 0.95rem;
  }

  .monitoring-mobile-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin-top: 0.95rem;
  }

  .monitoring-mobile-footer-copy {
    display: grid;
    gap: 0.14rem;
  }

  .monitoring-mobile-footer-label {
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--monitor-soft-text);
  }

  .monitoring-mobile-footer-value {
    color: var(--monitor-heading);
    font-size: 0.85rem;
    font-weight: 600;
  }

  .monitoring-empty-state {
    padding: 2rem;
    text-align: center;
  }

  @media (max-width: 1199.98px) {
    .monitoring-header-card {
      padding: 1.25rem;
    }

    .monitoring-filter-shell {
      width: 100%;
    }
  }

  @media (max-width: 575.98px) {
    .monitoring-mentor-page .card {
      border-color: var(--bs-border-color);
      box-shadow: 0 0.45rem 1.1rem rgba(15, 23, 42, 0.06);
    }

    .monitoring-header-card,
    .monitoring-summary-card,
    .monitoring-mobile-card {
      background: var(--bs-card-bg);
      color: var(--bs-body-color);
    }

    .monitoring-header-card,
    .monitoring-summary-card,
    .monitoring-mobile-card {
      border-color: var(--bs-border-color);
    }

    .monitoring-title {
      font-size: 1.4rem;
      color: var(--bs-heading-color);
    }

    .monitoring-header-card {
      padding: 1rem;
    }

    .monitoring-filter-shell {
      padding: 0.85rem;
      background: var(--bs-body-bg);
      border-color: var(--bs-border-color);
    }

    .monitoring-filter-shell > div,
    .monitoring-filter-shell .d-flex.gap-2 {
      flex-basis: 100%;
      width: 100%;
    }

    .monitoring-filter-shell .d-flex.gap-2 .btn {
      flex: 1 1 0;
    }

    .monitoring-filter-shell .form-control,
    .monitoring-filter-shell .form-select {
      min-height: 2.65rem;
    }

    .monitoring-summary-card {
      padding: 0.95rem 1rem;
    }

    .monitoring-summary-value {
      font-size: 1.8rem;
      color: var(--bs-heading-color);
    }

    .monitoring-mobile-card {
      padding: 0.9rem;
      border-radius: 1rem;
      overflow: hidden;
    }

    .monitoring-mobile-card .card-header,
    .monitoring-mobile-card .card-body {
      background: transparent;
    }

    .monitoring-mobile-card .card-header {
      border-bottom-color: var(--bs-border-color);
    }

    .monitoring-mobile-name,
    .monitoring-mobile-meta-row strong {
      color: var(--bs-heading-color);
    }

    .monitoring-mobile-email,
    .monitoring-mobile-meta-row span {
      color: var(--bs-secondary-color);
    }

    .monitoring-mobile-meta-row {
      background: var(--bs-tertiary-bg);
    }

    .monitoring-mobile-stat {
      background: #ffffff;
      border-color: rgba(15, 23, 42, 0.08);
    }

    .monitoring-mobile-note {
      background: rgba(91, 110, 240, 0.07);
      border-color: rgba(91, 110, 240, 0.16);
    }

    .monitoring-summary-badge,
    .monitoring-category-badge {
      background: var(--bs-tertiary-bg);
      color: var(--bs-heading-color);
    }

    .monitoring-avatar {
      background: var(--bs-tertiary-bg);
      color: var(--monitor-primary);
    }
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

  <div class="col-12">
    <div class="row g-3 row-cols-2 row-cols-xl-5">
      @foreach ($summary as $item)
        <div class="col">
          <div class="card monitoring-summary-card">
        <span class="monitoring-summary-badge bg-label-{{ $item['badge'] }} text-{{ $item['badge'] }}">
          <span class="dot"></span>{{ $item['label'] }}
        </span>
        <div class="monitoring-summary-value">{{ $item['count'] }}</div>
        <p class="monitoring-soft-text small mb-0 mt-2">{{ $summaryDescriptions[$item['key']] ?? 'Ringkasan status intern hari ini.' }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <div class="col-12">
    <div class="card monitoring-table-card d-none d-xl-block">
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

    <div class="monitoring-mobile-list d-xl-none mt-4">
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
        <div class="monitoring-mobile-card card">
          <div class="monitoring-mobile-header">
            <div class="d-flex align-items-center gap-3 min-w-0 flex-grow-1">
              <span class="monitoring-avatar">{{ strtoupper($initials ?: 'IN') }}</span>
              <div class="min-w-0">
                <div class="monitoring-mobile-kicker">Absensi Hari Ini</div>
                <div class="monitoring-mobile-name text-truncate">{{ $intern->name }}</div>
                <small class="monitoring-mobile-email monitoring-soft-text text-truncate d-block">{{ $intern->user?->email ?? $intern->email ?? '-' }}</small>
              </div>
            </div>
            <span class="badge rounded-pill bg-label-{{ $rowStatus['badge'] }}">{{ $rowStatus['label'] }}</span>
          </div>

          <div class="monitoring-mobile-badges">
            <span class="monitoring-mobile-badge">{{ $intern->division->name ?? 'Tanpa Divisi' }}</span>
            <span class="monitoring-mobile-badge">{{ $categoryLabel }}</span>
          </div>

          <div class="monitoring-mobile-note">
            <div class="monitoring-mobile-note-title">Kondisi Hari Ini</div>
            <div class="monitoring-mobile-note-text">
              {{ $rowStatus['meta'] }}
            </div>
          </div>

          <div class="monitoring-mobile-stat-grid">
            <div class="monitoring-mobile-stat">
              <span class="monitoring-mobile-stat-label">Check In</span>
              <div class="monitoring-mobile-stat-value">{{ $todayAttendance?->check_in_at?->format('H:i') ?? '-' }}</div>
            </div>
            <div class="monitoring-mobile-stat">
              <span class="monitoring-mobile-stat-label">Check Out</span>
              <div class="monitoring-mobile-stat-value">{{ $todayAttendance?->check_out_at?->format('H:i') ?? '-' }}</div>
            </div>
            <div class="monitoring-mobile-stat monitoring-mobile-stat--wide">
              <div>
                <span class="monitoring-mobile-stat-label">Total Kehadiran</span>
                <div class="monitoring-mobile-stat-value">{{ $intern->attendance_records_count }}/{{ $workingDays }} Hari</div>
              </div>
              <div class="text-end">
                <span class="monitoring-mobile-stat-label">Ringkasan</span>
                <div class="monitoring-mobile-stat-value">{{ $rowStatus['label'] }}</div>
              </div>
            </div>
          </div>

          <div class="monitoring-mobile-footer mt-3">
            <div class="monitoring-mobile-footer-copy">
              <span class="monitoring-mobile-footer-label">Ringkasan bulan berjalan</span>
              <span class="monitoring-mobile-footer-value">{{ $todayAttendance ? 'Tap untuk detail lengkap hari ini' : 'Menunggu catatan absensi masuk' }}</span>
            </div>
            <a href="{{ route('mentor.attendances.show', $intern) }}" class="btn btn-primary btn-sm monitoring-detail-btn">
              Lihat Detail
            </a>
          </div>
        </div>
      @empty
        <div class="card monitoring-mobile-card text-center">
          <h6 class="mb-2">Belum Ada Intern yang Sesuai</h6>
          <p class="monitoring-soft-text mb-0">Coba ubah pencarian atau kategori untuk melihat data intern lainnya.</p>
        </div>
      @endforelse

      @if ($interns->hasPages())
        <div class="d-flex justify-content-center pt-2">
          {{ $interns->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
