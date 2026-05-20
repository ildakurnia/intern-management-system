@extends('layouts/contentNavbarLayout')

@section('title', 'Monitoring Absensi Intern')

@section('page-style')
<style>
  .monitoring-admin-page {
    --monitor-primary: #4338ca;
    --monitor-primary-soft: #eef0ff;
    --monitor-border: rgba(82, 88, 126, 0.14);
    --monitor-text-soft: #8d93ac;
  }

  .monitoring-admin-page .card {
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
    width: 100%;
    min-width: 0;
    table-layout: auto;
  }

  .monitoring-table-card thead th {
    font-size: 0.73rem;
    letter-spacing: 0.035em;
    text-transform: uppercase;
    color: #4c5675;
    background: #f6f7fc;
    border-bottom: 0;
    padding: 0.85rem 0.7rem;
    white-space: nowrap;
  }

  .monitoring-table-card tbody td {
    padding: 0.85rem 0.7rem;
    border-color: rgba(90, 96, 141, 0.1);
    vertical-align: middle;
  }

  .monitoring-col-intern {
    width: auto;
  }

  .monitoring-col-divisi {
    width: auto;
  }

  .monitoring-col-kategori {
    width: auto;
  }

  .monitoring-col-status {
    width: auto;
    white-space: normal;
  }

  .monitoring-col-checkin,
  .monitoring-col-checkout {
    width: auto;
    white-space: nowrap;
  }

  .monitoring-col-total {
    width: auto;
    white-space: normal;
  }

  .monitoring-col-aksi {
    width: auto;
    white-space: nowrap;
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
    max-width: 100%;
  }

  .monitoring-attendance-total {
    font-size: 1.05rem;
    font-weight: 700;
    color: #171f39;
  }

  .monitoring-detail-btn {
    min-width: 0;
    padding-inline: 0.75rem;
    border-radius: 0.8rem;
  }

  .monitoring-table-card .table-responsive {
    overflow-x: hidden;
  }

  .monitoring-empty-state {
    padding: 2rem;
    text-align: center;
  }

  @media (max-width: 767.98px) {
    .monitoring-admin-page .card {
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

    .monitoring-title,
    .monitoring-mobile-name {
      color: var(--bs-heading-color);
    }

    .monitoring-soft-text,
    .monitoring-mobile-email {
      color: var(--bs-secondary-color);
    }

    .monitoring-filter-shell {
      background: var(--bs-body-bg);
      border-color: var(--bs-border-color);
    }

    .monitoring-filter-shell label {
      color: var(--bs-secondary-color);
    }

    .monitoring-filter-shell .form-control,
    .monitoring-filter-shell .form-select {
      background: var(--bs-card-bg);
    }

    .monitoring-summary-badge {
      background: var(--bs-tertiary-bg);
      color: var(--bs-heading-color) !important;
    }

    .monitoring-summary-value {
      color: var(--bs-heading-color);
    }

    .monitoring-mobile-shell {
      display: grid;
      gap: 1rem;
    }

    .monitoring-mobile-card {
      padding: 1rem;
      border-radius: 1rem;
      border: 1px solid var(--bs-border-color);
      background: #fff;
      overflow: hidden;
      box-shadow: 0 0.45rem 1.1rem rgba(15, 23, 42, 0.06);
      display: grid;
      gap: 0.95rem;
    }

    .monitoring-mobile-head {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 0.75rem;
    }

    .monitoring-mobile-name {
      margin: 0;
      font-size: 1rem;
      font-weight: 800;
      line-height: 1.25;
      color: var(--bs-heading-color);
    }

    .monitoring-mobile-email {
      margin-top: 0.2rem;
      font-size: 0.875rem;
      color: var(--bs-secondary-color);
      word-break: break-word;
    }

    .monitoring-mobile-badges {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
    }

    .monitoring-mobile-badges .badge {
      border-radius: 999px;
    }

    .monitoring-mobile-stack {
      display: grid;
      gap: 0.75rem;
    }

    .monitoring-mobile-meta-list {
      display: grid;
      gap: 0.55rem;
      padding-bottom: 0.85rem;
      border-bottom: 1px solid var(--bs-border-color);
    }

    .monitoring-mobile-meta-row {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 0.75rem;
    }

    .monitoring-mobile-meta-row span,
    .monitoring-mobile-tile span {
      display: block;
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      font-weight: 700;
      color: var(--bs-secondary-color);
    }

    .monitoring-mobile-meta-row strong {
      text-align: right;
      font-size: 0.9rem;
      font-weight: 700;
      color: var(--bs-heading-color);
    }

    .monitoring-mobile-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 0.65rem;
    }

    .monitoring-mobile-tile {
      padding: 0.8rem 0.85rem;
      border-radius: 0.9rem;
      border: 1px solid var(--bs-border-color);
      background: #fff;
      min-width: 0;
    }

    .monitoring-mobile-tile strong {
      display: block;
      margin-top: 0.25rem;
      font-size: 0.94rem;
      font-weight: 700;
      line-height: 1.35;
      color: var(--bs-heading-color);
      word-break: break-word;
    }

    .monitoring-mobile-tile--wide {
      grid-column: span 2;
    }

    .monitoring-mobile-actions {
      display: grid;
      gap: 0.6rem;
    }

    .monitoring-mobile-actions .btn {
      width: 100%;
      border-radius: 0.85rem;
    }

    .monitoring-mobile-empty .monitoring-mobile-actions {
      display: none;
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
      'hadir' => 'Intern yang absennya terekam tepat waktu hari ini.',
      'terlambat' => 'Intern yang check in melewati jam masuk.',
      'izin' => 'Intern dengan pengajuan izin pada tanggal hari ini.',
      'sakit' => 'Intern dengan pengajuan sakit pada tanggal hari ini.',
      'belum_absen' => 'Intern yang belum memiliki catatan absensi hari ini.',
  ];
@endphp

<div class="monitoring-admin-page row g-6">
  <div class="col-12">
    <div class="card monitoring-header-card">
      <div class="d-flex flex-column flex-xl-row justify-content-between gap-4">
        <div>
          <h3 class="monitoring-title">Monitoring Absensi Semua Intern</h3>
          <p class="monitoring-soft-text mb-0">Pantau status absensi harian setiap intern dan lanjutkan ke detail riwayat per orang saat diperlukan.</p>
          <small class="monitoring-soft-text d-block mt-2">Snapshot hari ini: {{ $monitorDate }}</small>
        </div>

        <form class="monitoring-filter-shell d-flex flex-wrap align-items-end gap-3 m-0 ims-mobile-filter" method="GET">
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
          <div class="ims-filter-actions">
            <button type="submit" class="btn btn-primary px-4">
              <i class="ri ri-search-line me-1"></i>Terapkan
            </button>
            <a href="{{ route('admin.attendances.index') }}" class="btn btn-outline-secondary px-4">
              <i class="ri ri-refresh-line me-1"></i>Reset
            </a>
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
      <div class="table-responsive ims-card-table-wrap d-none d-md-block">
        <table class="table align-middle ims-card-table">
          <thead>
            <tr>
              <th class="monitoring-col-intern">Intern</th>
              <th class="monitoring-col-divisi">Divisi</th>
              <th class="monitoring-col-kategori">Kategori</th>
              <th class="monitoring-col-status">Status Hari Ini</th>
              <th class="monitoring-col-checkin">Check In</th>
              <th class="monitoring-col-checkout">Check Out</th>
              <th class="monitoring-col-total">Total Kehadiran</th>
              <th class="monitoring-col-aksi">Aksi</th>
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
                <td data-label="Intern" class="ims-card-primary monitoring-col-intern">
                  <div class="d-flex align-items-center gap-3">
                    <span class="monitoring-avatar">{{ strtoupper($initials ?: 'IN') }}</span>
                    <div>
                      <div class="fw-semibold">{{ $intern->name }}</div>
                      <small class="monitoring-soft-text">{{ $intern->user?->email ?? $intern->email ?? '-' }}</small>
                    </div>
                  </div>
                </td>
                <td data-label="Divisi" class="monitoring-col-divisi">{{ $intern->division->name ?? '-' }}</td>
                <td data-label="Kategori" class="monitoring-col-kategori"><span class="monitoring-category-badge">{{ $categoryLabel }}</span></td>
                <td data-label="Status Hari Ini" class="monitoring-col-status">
                  <span class="badge rounded-pill bg-label-{{ $rowStatus['badge'] }}">{{ $rowStatus['label'] }}</span>
                  <div class="small monitoring-soft-text mt-1">{{ $rowStatus['meta'] }}</div>
                </td>
                <td data-label="Check In" class="monitoring-col-checkin">{{ $todayAttendance?->check_in_at?->format('H:i') ?? '-' }}</td>
                <td data-label="Check Out" class="monitoring-col-checkout">{{ $todayAttendance?->check_out_at?->format('H:i') ?? '-' }}</td>
                <td data-label="Total Kehadiran" class="monitoring-col-total">
                  <div class="monitoring-attendance-total">{{ $intern->attendance_records_count }}/{{ $workingDays }} Hari</div>
                  <small class="monitoring-soft-text">Ringkasan bulan berjalan</small>
                </td>
                <td data-label="Aksi" class="ims-card-actions monitoring-col-aksi">
                  <a href="{{ route('admin.attendances.show', $intern) }}" class="btn btn-primary btn-sm monitoring-detail-btn">
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

      <div class="d-md-none p-3">
        <div class="monitoring-mobile-shell">
          @forelse ($interns as $intern)
            @php
              $todayAttendance = $intern->attendances->first();
              $rowStatus = $resolveStatus($todayAttendance);
              $categoryLabel = ucfirst($intern->type ?? 'intern');
            @endphp

            <div class="monitoring-mobile-card">
              <div class="monitoring-mobile-head">
                <div class="min-w-0">
                  <h6 class="monitoring-mobile-name text-truncate">{{ $intern->name }}</h6>
                  <div class="monitoring-mobile-email text-truncate">{{ $intern->user?->email ?? $intern->email ?? '-' }}</div>
                </div>
                <span class="badge bg-label-{{ $rowStatus['badge'] }} rounded-pill flex-shrink-0">
                  {{ $rowStatus['label'] }}
                </span>
              </div>

              <div class="monitoring-mobile-stack">
                <div class="monitoring-mobile-meta-list">
                  <div class="monitoring-mobile-meta-row">
                    <span>Divisi</span>
                    <strong>{{ $intern->division->name ?? '-' }}</strong>
                  </div>
                  <div class="monitoring-mobile-meta-row">
                    <span>Kategori</span>
                    <strong>{{ $categoryLabel }}</strong>
                  </div>
                  <div class="monitoring-mobile-meta-row">
                    <span>Status</span>
                    <strong>{{ $rowStatus['label'] }}</strong>
                  </div>
                </div>

                <div class="monitoring-mobile-grid">
                  <div class="monitoring-mobile-tile">
                    <span>Check In</span>
                    <strong>{{ $todayAttendance?->check_in_at?->format('H:i') ?? '-' }}</strong>
                  </div>
                  <div class="monitoring-mobile-tile">
                    <span>Check Out</span>
                    <strong>{{ $todayAttendance?->check_out_at?->format('H:i') ?? '-' }}</strong>
                  </div>
                  <div class="monitoring-mobile-tile">
                    <span>Total Kehadiran</span>
                    <strong>{{ $intern->attendance_records_count }}/{{ $workingDays }} Hari</strong>
                  </div>
                  <div class="monitoring-mobile-tile monitoring-mobile-tile--wide">
                    <span>Catatan</span>
                    <strong>{{ $rowStatus['meta'] }}</strong>
                  </div>
                </div>

                <div class="monitoring-mobile-actions">
                  <a href="{{ route('admin.attendances.show', $intern) }}" class="btn btn-primary">
                    Lihat Detail
                  </a>
                </div>
              </div>
            </div>
          @empty
            <div class="monitoring-mobile-card monitoring-mobile-empty text-center">
              <h6 class="mb-2">Belum Ada Intern yang Sesuai</h6>
              <p class="monitoring-soft-text mb-0">Coba ubah pencarian atau kategori untuk melihat data intern lainnya.</p>
            </div>
          @endforelse
        </div>
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
