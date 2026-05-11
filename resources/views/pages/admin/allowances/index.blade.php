@extends('layouts/contentNavbarLayout')

@section('title', 'Uang Saku Mahasiswa')

@section('page-style')
<style>
  .allowance-page {
    --allowance-primary: #4338ca;
    --allowance-primary-deep: #3422cf;
    --allowance-primary-soft: #eef0ff;
    --allowance-border: rgba(82, 88, 126, 0.14);
    --allowance-border-strong: rgba(90, 96, 141, 0.12);
    --allowance-soft: #8d93ac;
    --allowance-card-bg: #ffffff;
    --allowance-card-bg-subtle: linear-gradient(180deg, #fbfcff 0%, #f8f9ff 100%);
    --allowance-table-head-bg: #f6f7fc;
    --allowance-title-color: #1f2744;
    --allowance-heading-color: #2a3557;
    --allowance-value-color: #161f39;
    --allowance-body-color: #5f6884;
    --allowance-badge-bg: #e9e8ff;
    --allowance-badge-color: #4b47d9;
    --allowance-chip-bg: #edf0ff;
    --allowance-chip-color: #5b6480;
    --allowance-avatar-bg: #edefff;
    --allowance-avatar-color: #4338ca;
    --allowance-avatar-success-bg: #dff9ea;
    --allowance-avatar-success-color: #11935b;
    --allowance-kicker-bg: #dbf7e9;
    --allowance-kicker-color: #0f9f56;
    --allowance-hover-bg: #f8f8ff;
    --allowance-shadow: 0 18px 45px rgba(31, 38, 69, 0.06);
  }

  html[data-bs-theme="dark"] .allowance-page {
    --allowance-primary-soft: rgba(93, 91, 255, 0.18);
    --allowance-border: rgba(219, 223, 255, 0.1);
    --allowance-border-strong: rgba(219, 223, 255, 0.12);
    --allowance-soft: #9ea6c6;
    --allowance-card-bg: #2a2740;
    --allowance-card-bg-subtle: linear-gradient(180deg, #2f2b48 0%, #29263f 100%);
    --allowance-table-head-bg: #312d4d;
    --allowance-title-color: #f4f5ff;
    --allowance-heading-color: #e7e9fb;
    --allowance-value-color: #ffffff;
    --allowance-body-color: #c4cae3;
    --allowance-badge-bg: rgba(93, 91, 255, 0.2);
    --allowance-badge-color: #bdb9ff;
    --allowance-chip-bg: rgba(237, 240, 255, 0.08);
    --allowance-chip-color: #d4d9f2;
    --allowance-avatar-bg: rgba(93, 91, 255, 0.18);
    --allowance-avatar-color: #cbc7ff;
    --allowance-avatar-success-bg: rgba(17, 147, 91, 0.18);
    --allowance-avatar-success-color: #73d6ab;
    --allowance-kicker-bg: rgba(15, 159, 86, 0.18);
    --allowance-kicker-color: #7ce2b4;
    --allowance-hover-bg: rgba(255, 255, 255, 0.04);
    --allowance-shadow: 0 18px 45px rgba(0, 0, 0, 0.24);
  }

  .allowance-page .card {
    border-radius: 1.5rem;
    border: 1px solid var(--allowance-border);
    box-shadow: var(--allowance-shadow);
  }

  .allowance-header-card,
  .allowance-promo-card,
  .allowance-table-card {
    background: var(--allowance-card-bg);
  }

  .allowance-header-card {
    padding: 1.6rem;
  }

  .allowance-title {
    font-size: 1.9rem;
    color: var(--allowance-title-color);
    margin-bottom: 0.45rem;
  }

  .allowance-soft-text {
    color: var(--allowance-soft);
  }

  .allowance-header-copy {
    max-width: 46rem;
    margin-bottom: 1.35rem;
  }

  .allowance-filter-shell {
    border-radius: 1.2rem;
    width: 100%;
    max-width: none;
    padding: 1.25rem 1.25rem 1.05rem;
    border: 1px solid var(--allowance-border-strong);
    background: var(--allowance-card-bg-subtle);
    box-shadow: 0 12px 32px rgba(34, 42, 72, 0.05);
  }

  .allowance-filter-grid {
    display: grid;
    grid-template-columns: minmax(0, 13rem) minmax(0, 1fr) auto;
    gap: 1rem;
    align-items: end;
  }

  .allowance-filter-field {
    min-width: 0;
  }

  .allowance-filter-field.search-field {
    justify-self: start;
    width: 100%;
  }

  .allowance-filter-shell label {
    display: block;
    margin-bottom: 0.45rem;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--allowance-heading-color);
  }

  .allowance-filter-shell .form-control,
  .allowance-filter-shell .form-select {
    min-height: 2.75rem;
    border-radius: 1rem;
  }

  .allowance-filter-shell .form-control::placeholder {
    color: var(--allowance-soft);
  }

  .allowance-filter-actions {
    display: inline-flex;
    gap: 0.65rem;
    align-items: center;
    justify-self: end;
  }

  .allowance-filter-actions .btn-primary {
    box-shadow: 0 10px 24px rgba(67, 56, 202, 0.18);
  }

  .allowance-reset-btn {
    width: 2.85rem;
    height: 2.85rem;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.95rem;
  }

  .allowance-print-shell {
    display: grid;
    gap: 1rem;
    margin-top: 1.1rem;
    padding-top: 1.15rem;
    border-top: 1px solid rgba(120, 134, 178, 0.18);
  }

  .allowance-print-label {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
    letter-spacing: 0;
    text-transform: none;
    color: var(--allowance-heading-color);
    font-weight: 700;
  }

  .allowance-summary-card {
    padding: 1.45rem;
    background: var(--allowance-card-bg);
    height: 100%;
  }

  .allowance-summary-icon {
    width: 3.2rem;
    height: 3.2rem;
    border-radius: 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    margin-bottom: 1rem;
  }

  .allowance-summary-icon.primary {
    background: #eef0ff;
    color: #3d39d7;
  }

  .allowance-summary-icon.success {
    background: #ebf8ee;
    color: #117a38;
  }

  .allowance-summary-icon.warning {
    background: #fff4e3;
    color: #b56a00;
  }

  .allowance-summary-icon.info {
    background: #e7f7ff;
    color: #0b7ea7;
  }

  html[data-bs-theme="dark"] .allowance-page .allowance-summary-icon.primary {
    background: rgba(93, 91, 255, 0.18);
    color: #cbc7ff;
  }

  html[data-bs-theme="dark"] .allowance-page .allowance-summary-icon.success {
    background: rgba(17, 147, 91, 0.18);
    color: #73d6ab;
  }

  html[data-bs-theme="dark"] .allowance-page .allowance-summary-icon.warning {
    background: rgba(181, 106, 0, 0.18);
    color: #f3c77d;
  }

  html[data-bs-theme="dark"] .allowance-page .allowance-summary-icon.info {
    background: rgba(11, 126, 167, 0.18);
    color: #8cd7f1;
  }

  .allowance-summary-kicker {
    display: inline-flex;
    align-items: center;
    padding: 0.34rem 0.7rem;
    border-radius: 999px;
    background: var(--allowance-kicker-bg);
    color: var(--allowance-kicker-color);
    font-size: 0.74rem;
    font-weight: 800;
  }

  .allowance-summary-value {
    margin-top: 1rem;
    font-size: 2.25rem;
    line-height: 1.1;
    color: var(--allowance-value-color);
    font-weight: 800;
  }

  .allowance-summary-card.featured {
    background: linear-gradient(135deg, var(--allowance-primary-deep) 0%, #4c39dd 100%);
    color: #fff;
    box-shadow: 0 22px 48px rgba(67, 56, 202, 0.22);
  }

  .allowance-summary-card.featured .allowance-summary-value,
  .allowance-summary-card.featured .allowance-soft-text,
  .allowance-summary-card.featured .allowance-summary-title {
    color: #fff !important;
  }

  .allowance-summary-card.featured .allowance-summary-icon {
    background: rgba(255, 255, 255, 0.14);
    color: #fff;
  }

  .allowance-summary-title {
    font-size: 0.95rem;
    color: var(--allowance-heading-color);
  }

  .allowance-table-card {
    padding: 0;
    overflow: hidden;
  }

  .allowance-table-head {
    padding: 1.4rem 1.5rem 1rem;
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    align-items: center;
    border-bottom: 1px solid var(--allowance-border);
  }

  .allowance-table-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.35rem 0.8rem;
    border-radius: 999px;
    background: var(--allowance-badge-bg);
    color: var(--allowance-badge-color);
    font-weight: 700;
    font-size: 0.74rem;
    text-transform: uppercase;
  }

  .allowance-table-card .table {
    margin-bottom: 0;
  }

  .allowance-table-card thead th {
    font-size: 0.8rem;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: var(--allowance-body-color);
    background: var(--allowance-table-head-bg);
    border-bottom: 0;
    padding-top: 1.1rem;
    padding-bottom: 1.1rem;
  }

  .allowance-table-card tbody td {
    padding-top: 1.15rem;
    padding-bottom: 1.15rem;
    border-color: var(--allowance-border);
    vertical-align: middle;
  }

  .allowance-avatar {
    width: 2.7rem;
    height: 2.7rem;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: var(--allowance-avatar-bg);
    color: var(--allowance-avatar-color);
    font-weight: 800;
  }

  .allowance-avatar.success {
    background: var(--allowance-avatar-success-bg);
    color: var(--allowance-avatar-success-color);
  }

  .allowance-chip {
    display: inline-flex;
    align-items: center;
    padding: 0.26rem 0.55rem;
    border-radius: 0.55rem;
    background: var(--allowance-chip-bg);
    color: var(--allowance-chip-color);
    font-size: 0.75rem;
    font-weight: 700;
    margin-bottom: 0.45rem;
  }

  .allowance-attendance-total {
    font-size: 1.08rem;
    font-weight: 800;
    color: var(--allowance-value-color);
  }

  .allowance-attendance-breakdown {
    display: flex;
    gap: 1.25rem;
    flex-wrap: wrap;
    margin-top: 0.4rem;
  }

  .allowance-attendance-breakdown span {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.82rem;
  }

  .allowance-attendance-breakdown .dot {
    width: 0.45rem;
    height: 0.45rem;
    border-radius: 999px;
    display: inline-block;
  }

  .allowance-rate-line {
    color: var(--allowance-body-color);
    font-size: 0.82rem;
  }

  .allowance-total-line {
    font-size: 1.32rem;
    font-weight: 800;
    color: #4037d7;
    line-height: 1.1;
    margin-top: 0.25rem;
  }

  html[data-bs-theme="dark"] .allowance-page .allowance-total-line {
    color: #bdb9ff;
  }

  .allowance-max-line {
    color: var(--allowance-soft);
    font-size: 0.82rem;
    margin-top: 0.35rem;
  }

  .allowance-icon-btn {
    width: 2.7rem;
    height: 2.7rem;
    border-radius: 0.9rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--allowance-border-strong);
    color: var(--allowance-heading-color);
    background: var(--allowance-card-bg);
  }

  .allowance-icon-btn:hover {
    color: var(--allowance-primary);
    border-color: rgba(67, 56, 202, 0.26);
    background: var(--allowance-hover-bg);
  }

  .allowance-empty-state {
    padding: 2rem;
    text-align: center;
  }

  .allowance-action-group {
    display: flex;
    gap: 0.65rem;
    flex-wrap: wrap;
    align-items: center;
  }

  .allowance-print-group {
    display: flex;
    width: 100%;
    gap: 0.65rem;
    flex-wrap: wrap;
    justify-content: flex-start;
  }

  .allowance-print-group .btn {
    flex: 0 1 auto;
    min-width: 10.5rem;
  }

  .layout-menu-collapsed .allowance-page .allowance-header-copy {
    max-width: 54rem;
  }

  .layout-menu-collapsed .allowance-page .allowance-filter-shell {
    width: 100%;
    max-width: none;
  }

  .layout-menu-collapsed .allowance-page .allowance-filter-grid {
    grid-template-columns: minmax(0, 14rem) minmax(0, 1fr) auto;
  }

  .layout-menu-collapsed .allowance-page .allowance-print-group {
    justify-content: flex-start;
  }

  .allowance-promo-card {
    padding: 1.65rem;
    background: linear-gradient(135deg, rgba(69, 58, 220, 0.95) 0%, rgba(103, 90, 255, 0.92) 100%);
    color: #fff;
    overflow: hidden;
    position: relative;
  }

  .allowance-promo-card::after {
    content: '';
    position: absolute;
    right: -2rem;
    bottom: -2rem;
    width: 8rem;
    height: 8rem;
    border-radius: 2rem;
    background: rgba(255, 255, 255, 0.09);
  }

  .allowance-promo-card p {
    color: rgba(255, 255, 255, 0.8);
  }

  @media (max-width: 991.98px) {
    .allowance-filter-shell {
      max-width: none;
    }

    .allowance-filter-grid,
    .allowance-table-head {
      grid-template-columns: 1fr;
      align-items: stretch;
    }

    .allowance-filter-grid {
      gap: 0.75rem;
    }

    .allowance-filter-field.search-field {
      width: 100%;
    }

    .allowance-filter-actions {
      width: 100%;
    }

    .allowance-filter-actions .btn {
      flex: 1;
    }

    .allowance-print-group {
      justify-content: stretch;
    }

    .allowance-print-group .btn {
      flex: 1 1 12rem;
    }

    .allowance-reset-btn {
      flex: 0 0 2.85rem !important;
    }
  }
</style>
@endsection

@section('content')
@php
  $monthLabel = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->locale('id')->translatedFormat('F Y');
  $summaryIconMap = [
    0 => ['icon' => 'ri-user-star-line', 'class' => 'primary', 'kicker' => 'Aktif'],
    1 => ['icon' => 'ri-calendar-check-line', 'class' => 'warning', 'kicker' => null],
    2 => ['icon' => 'ri-wallet-3-line', 'class' => 'info', 'kicker' => null],
  ];
@endphp

<div class="allowance-page row g-6">
  <div class="col-12">
    <div class="card allowance-header-card">
      <div class="allowance-header-copy">
        <h3 class="allowance-title">Manajemen Uang Saku Mahasiswa</h3>
        <p class="allowance-soft-text mb-0">Kelola dan monitor pencairan dana saku mahasiswa magang berdasarkan tingkat kehadiran secara real-time.</p>
      </div>

      <form class="allowance-filter-shell m-0" method="GET">
        <div class="allowance-filter-grid">
          <div class="allowance-filter-field">
            <label for="allowance-month">Pilih Bulan</label>
            <input
              id="allowance-month"
              type="month"
              name="month"
              value="{{ $selectedMonth }}"
              class="form-control" />
          </div>
          <div class="allowance-filter-field search-field">
            <label for="allowance-search">Cari Nama Mahasiswa</label>
            <input
              id="allowance-search"
              type="text"
              name="search"
              value="{{ $selectedSearch }}"
              class="form-control"
              placeholder="Masukkan nama atau NIM mahasiswa..." />
          </div>
          <div class="allowance-filter-actions">
            <button type="submit" class="btn btn-primary px-4"><i class="ri ri-filter-3-line me-1"></i>Terapkan</button>
            <a href="{{ route('admin.allowances.index') }}" class="btn btn-outline-secondary allowance-reset-btn" aria-label="Reset filter">
              <i class="ri ri-refresh-line"></i>
            </a>
          </div>
        </div>

        <div class="allowance-print-shell">
          <div class="allowance-print-label mb-2"><i class="ri ri-printer-line me-1"></i>Opsi Pencetakan Laporan</div>
          <div class="allowance-print-group">
            <a
              href="{{ route('admin.allowances.print', ['month' => $selectedMonth, 'search' => $selectedSearch]) }}"
              target="_blank"
              class="btn btn-outline-primary px-4">
              <i class="ri ri-printer-line me-1"></i>Cetak Hasil Filter
            </a>
            <a
              href="{{ route('admin.allowances.print', ['month' => $selectedMonth]) }}"
              target="_blank"
              class="btn btn-outline-secondary px-4">
              <i class="ri ri-group-line me-1"></i>Cetak Semua Mahasiswa
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>

  @foreach ($summary as $index => $item)
    @php
      $iconConfig = $summaryIconMap[$index] ?? ['icon' => 'ri-bar-chart-box-line', 'class' => 'primary', 'kicker' => null];
      $isFeaturedSummary = $index === count($summary) - 1;
    @endphp
    <div class="col-md-6 col-xl-4">
      <div class="card allowance-summary-card {{ $isFeaturedSummary ? 'featured' : '' }}">
        <div class="d-flex justify-content-between align-items-start gap-3">
          <span class="allowance-summary-icon {{ $isFeaturedSummary ? '' : $iconConfig['class'] }}">
            <i class="ri {{ $iconConfig['icon'] }}"></i>
          </span>
          @if ($iconConfig['kicker'])
            <span class="allowance-summary-kicker">{{ $iconConfig['kicker'] }}</span>
          @endif
        </div>
        <div class="allowance-summary-value">{{ $item['count'] }}</div>
        <div class="allowance-summary-title mt-1">{{ $item['label'] }}</div>
        <p class="allowance-soft-text small mb-0 mt-2">{{ $item['meta'] }}</p>
      </div>
    </div>
  @endforeach

  <div class="col-12">
    <div class="card allowance-table-card">
      <div class="allowance-table-head">
        <div>
          <h5 class="mb-1">Daftar Mahasiswa Polibatam</h5>
          <p class="allowance-soft-text mb-0">Ringkasan mahasiswa eligible yang digunakan untuk menghitung dan mencetak uang saku.</p>
        </div>
        <span class="allowance-table-badge">Update: {{ now()->locale('id')->translatedFormat('d M Y') }}</span>
      </div>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Mahasiswa</th>
              <th>Divisi & Institusi</th>
              <th>Kehadiran</th>
              <th>Tarif & Total</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($allowances as $row)
              @php
                $intern = $row['intern'];
                $initials = collect(explode(' ', trim($intern->name)))
                  ->filter()
                  ->take(2)
                  ->map(fn ($part) => mb_substr($part, 0, 1))
                  ->implode('');
                $attendanceProgress = max($row['max_workdays'], 1);
                $divisionCode = trim((string) ($intern->division->code ?? ''));
                $divisionName = trim((string) ($intern->division->name ?? '-'));
                $showDivisionChip = $divisionCode !== '' && strcasecmp($divisionCode, $divisionName) !== 0;
              @endphp
              <tr>
                <td>
                  <div class="d-flex align-items-center gap-3">
                    <span class="allowance-avatar success">{{ strtoupper($initials ?: 'IN') }}</span>
                    <div>
                      <div class="fw-semibold">{{ $intern->name }}</div>
                      <small class="allowance-soft-text">{{ $intern->nim ?: ($intern->email ?? '-') }}</small>
                    </div>
                  </div>
                </td>
                <td>
                  @if ($showDivisionChip)
                    <div class="allowance-chip">{{ $divisionCode }}</div>
                  @endif
                  <div class="fw-semibold">{{ $divisionName }}</div>
                  <small class="allowance-soft-text">{{ $row['institution_label'] }}</small>
                </td>
                <td>
                  <div class="allowance-attendance-total">{{ $row['counted_days'] }}/{{ $attendanceProgress }} hari</div>
                  <div class="allowance-attendance-breakdown">
                    <span class="text-success">
                      <span class="dot" style="background:#12a150;"></span>{{ $row['present_days'] }}
                    </span>
                    <span class="text-danger">
                      <span class="dot" style="background:#d63535;"></span>Terlambat {{ $row['late_days'] }}
                    </span>
                  </div>
                </td>
                <td>
                  <div class="allowance-total-line">{{ $row['allowance_amount_label'] }}</div>
                  <div class="allowance-max-line">Max: {{ $row['max_amount_label'] }}</div>
                </td>
                <td>
                  <div class="allowance-action-group">
                    <a href="{{ route('admin.allowances.show.print', ['intern' => $intern, 'month' => $selectedMonth]) }}" target="_blank" class="allowance-icon-btn" aria-label="Cetak PDF {{ $intern->name }}">
                      <i class="ri ri-file-pdf-line"></i>
                    </a>
                    <a href="{{ route('admin.allowances.show', ['intern' => $intern, 'month' => $selectedMonth]) }}" class="btn btn-primary">
                      Lihat Detail
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8">
                  <div class="allowance-empty-state">
                    <h6 class="mb-2">Belum Ada Mahasiswa Eligible</h6>
                    <p class="allowance-soft-text mb-0">Mahasiswa Polibatam yang memakai institusi resmi akan muncul di daftar ini.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if ($allowances->hasPages())
        <div class="px-4 py-3 border-top d-flex justify-content-end">
          {{ $allowances->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </div>
  </div>

  <div class="col-12">
    <div class="card allowance-promo-card">
      <h3 class="text-white mb-2">Automatisasi Laporan Bulanan</h3>
      <p class="mb-0">Modul uang saku membaca kehadiran mahasiswa secara otomatis, lalu menyiapkan rekap dan cetak PDF tanpa menghitung manual satu per satu.</p>
    </div>
  </div>
</div>
@endsection
