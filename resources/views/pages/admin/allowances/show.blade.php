@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Uang Saku Intern')

@section('page-style')
<style>
  .allowance-detail-page {
    --allowance-primary: #4338ca;
    --allowance-primary-deep: #3422cf;
    --allowance-primary-soft: #eef0ff;
    --allowance-border: rgba(82, 88, 126, 0.14);
    --allowance-soft: #8d93ac;
    --allowance-card-bg: #ffffff;
    --allowance-card-bg-subtle: #f6f7fc;
    --allowance-title-color: #1f2744;
    --allowance-heading-color: #2b3556;
    --allowance-value-color: #151e39;
    --allowance-body-color: #69748d;
    --allowance-shadow: 0 18px 45px rgba(31, 38, 69, 0.06);
  }

  html[data-bs-theme="dark"] .allowance-detail-page {
    --allowance-primary-soft: rgba(93, 91, 255, 0.18);
    --allowance-border: rgba(219, 223, 255, 0.1);
    --allowance-soft: #9ea6c6;
    --allowance-card-bg: #2a2740;
    --allowance-card-bg-subtle: #312d4d;
    --allowance-title-color: #f4f5ff;
    --allowance-heading-color: #e7e9fb;
    --allowance-value-color: #ffffff;
    --allowance-body-color: #c4cae3;
    --allowance-shadow: 0 18px 45px rgba(0, 0, 0, 0.24);
  }

  .allowance-detail-page .card {
    border-radius: 1.5rem;
    border: 1px solid var(--allowance-border);
    box-shadow: var(--allowance-shadow);
  }

  .allowance-hero-card,
  .allowance-summary-card,
  .allowance-calculation-card,
  .allowance-history-card,
  .allowance-footer-card {
    background: var(--allowance-card-bg);
  }

  .allowance-panel-title {
    font-size: 2rem;
    color: var(--allowance-title-color);
    margin-bottom: 0.35rem;
  }

  .allowance-soft-text {
    color: var(--allowance-soft);
  }

  .allowance-profile-card {
    padding: 1.5rem;
    height: 100%;
  }

  .allowance-profile-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 0.9rem;
    border-radius: 999px;
    background: var(--allowance-primary-soft);
    color: var(--allowance-primary);
    font-size: 0.78rem;
    font-weight: 700;
  }

  .allowance-profile-grid {
    margin-top: 1.2rem;
    border-top: 1px solid var(--allowance-border);
  }

  .allowance-profile-row {
    display: flex;
    justify-content: space-between;
    gap: 1.25rem;
    padding: 1rem 0;
    border-bottom: 1px solid var(--allowance-border);
  }

  .allowance-profile-row:last-child {
    border-bottom: 0;
    padding-bottom: 0;
  }

  .allowance-profile-label {
    font-size: 0.88rem;
    color: var(--allowance-body-color);
    flex: 0 0 32%;
  }

  .allowance-profile-value {
    flex: 1;
    text-align: right;
    font-weight: 700;
    color: var(--allowance-value-color);
  }

  .allowance-kpi-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1.15rem;
  }

  .allowance-kpi-card {
    padding: 1.35rem 1.4rem;
    min-height: 180px;
  }

  .allowance-kpi-icon {
    width: 3.65rem;
    height: 3.65rem;
    border-radius: 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.55rem;
    margin-bottom: 1.15rem;
  }

  .allowance-kpi-icon.primary {
    background: #eef1ff;
    color: #3a36d5;
  }

  .allowance-kpi-icon.success {
    background: #ebf8ee;
    color: #117a38;
  }

  .allowance-kpi-icon.warning {
    background: #fff4e3;
    color: #b56a00;
  }

  html[data-bs-theme="dark"] .allowance-detail-page .allowance-kpi-icon.primary {
    background: rgba(93, 91, 255, 0.18);
    color: #cbc7ff;
  }

  html[data-bs-theme="dark"] .allowance-detail-page .allowance-kpi-icon.success {
    background: rgba(17, 147, 91, 0.18);
    color: #73d6ab;
  }

  html[data-bs-theme="dark"] .allowance-detail-page .allowance-kpi-icon.warning {
    background: rgba(181, 106, 0, 0.18);
    color: #f3c77d;
  }

  .allowance-kpi-label {
    font-size: 0.95rem;
    color: var(--allowance-heading-color);
    margin-bottom: 0.35rem;
  }

  .allowance-kpi-value {
    font-size: 2.1rem;
    line-height: 1.05;
    font-weight: 800;
    color: var(--allowance-value-color);
  }

  .allowance-kpi-meta {
    margin-top: 0.45rem;
    color: var(--allowance-body-color);
  }

  .allowance-kpi-card.featured {
    background: linear-gradient(135deg, var(--allowance-primary-deep) 0%, #4a37dd 100%);
    color: #fff;
    box-shadow: 0 22px 48px rgba(67, 56, 202, 0.22);
  }

  .allowance-kpi-card.featured .allowance-kpi-label,
  .allowance-kpi-card.featured .allowance-kpi-value,
  .allowance-kpi-card.featured .allowance-kpi-meta {
    color: #fff;
  }

  .allowance-kpi-card.featured .allowance-kpi-icon {
    background: rgba(255, 255, 255, 0.12);
    color: #fff;
  }

  .allowance-calculation-card {
    padding: 1.65rem;
    border-style: dashed;
  }

  .allowance-section-caption {
    font-size: 0.82rem;
    text-transform: uppercase;
    letter-spacing: 0.16em;
    color: #4b51d9;
    text-align: center;
    margin-bottom: 0.7rem;
  }

  .allowance-calculation-expression {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1.25rem;
    flex-wrap: wrap;
    font-size: 2.15rem;
    font-weight: 800;
    color: #2833d1;
    line-height: 1.2;
  }

  .allowance-calculation-muted {
    text-align: center;
    margin-top: 0.9rem;
    color: var(--allowance-soft);
  }

  .allowance-history-card {
    padding: 0;
    overflow: hidden;
  }

  .allowance-history-head {
    padding: 1.45rem 1.55rem 1rem;
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    align-items: center;
  }

  .allowance-late-pill {
    display: inline-flex;
    align-items: center;
    padding: 0.4rem 0.8rem;
    border-radius: 999px;
    background: #ffe7e2;
    color: #cb4430;
    font-weight: 700;
    font-size: 0.82rem;
  }

  html[data-bs-theme="dark"] .allowance-detail-page .allowance-late-pill {
    background: rgba(203, 68, 48, 0.18);
    color: #ffad9f;
  }

  .allowance-history-card thead th {
    font-size: 0.8rem;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: var(--allowance-body-color);
    background: var(--allowance-card-bg-subtle);
    border-bottom: 0;
  }
  .allowance-history-card .table {
    margin-bottom: 0;
  }

  .allowance-history-card tbody td {
    vertical-align: middle;
    padding-top: 1rem;
    padding-bottom: 1rem;
  }

  .allowance-history-card tbody tr:hover {
    background: #fbfcff;
  }

  html[data-bs-theme="dark"] .allowance-detail-page .allowance-history-card tbody tr:hover {
    background: rgba(255, 255, 255, 0.03);
  }

  .allowance-status-pill {
    display: inline-flex;
    align-items: center;
    padding: 0.35rem 0.75rem;
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 700;
  }

  .allowance-status-pill.warning {
    background: #fff3df;
    color: #b56a00;
  }

  .allowance-status-pill.success {
    background: #ebf8ee;
    color: #117a38;
  }

  .allowance-status-pill.info {
    background: #e7f7ff;
    color: #0b7ea7;
  }

  .allowance-status-pill.danger {
    background: #ffe9e9;
    color: #c73939;
  }

  .allowance-status-pill.secondary {
    background: #eceff5;
    color: #69748d;
  }

  html[data-bs-theme="dark"] .allowance-detail-page .allowance-status-pill.warning {
    background: rgba(181, 106, 0, 0.18);
    color: #f3c77d;
  }

  html[data-bs-theme="dark"] .allowance-detail-page .allowance-status-pill.success {
    background: rgba(17, 122, 56, 0.18);
    color: #73d6ab;
  }

  html[data-bs-theme="dark"] .allowance-detail-page .allowance-status-pill.info {
    background: rgba(11, 126, 167, 0.18);
    color: #8cd7f1;
  }

  html[data-bs-theme="dark"] .allowance-detail-page .allowance-status-pill.danger {
    background: rgba(199, 57, 57, 0.18);
    color: #ffb0b0;
  }

  html[data-bs-theme="dark"] .allowance-detail-page .allowance-status-pill.secondary {
    background: rgba(236, 239, 245, 0.08);
    color: #c4cae3;
  }

  .allowance-history-metric {
    font-weight: 700;
    color: #17203d;
  }

  .allowance-footer-card {
    padding: 1.15rem 1.35rem;
  }

  .allowance-footer-meta {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    align-items: center;
    color: #69748d;
    font-size: 0.92rem;
  }

  @media (max-width: 1199.98px) {
    .allowance-kpi-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media (max-width: 767.98px) {
    .allowance-profile-row {
      flex-direction: column;
      gap: 0.4rem;
    }

    .allowance-profile-value {
      text-align: left;
    }

    .allowance-kpi-grid {
      grid-template-columns: 1fr;
    }

    .allowance-calculation-expression {
      font-size: 1.55rem;
      gap: 0.55rem;
    }

    .allowance-footer-meta {
      flex-direction: column;
      align-items: flex-start;
    }
  }
</style>
@endsection

@section('content')
@php
  $intern = $allowance['intern'];
  $monthLabel = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->locale('id')->translatedFormat('F Y');
  $lateEntries = $allowance['late_days'] ?? ($allowance['status_counts'][\App\Models\Attendance::STATUS_LATE] ?? 0);
  $documentId = sprintf(
    '%s-%s-%04d',
    $intern->division?->code ?: 'IMS',
    str_replace('-', '', $selectedMonth),
    $intern->id
  );
@endphp

<div class="allowance-detail-page row g-6">
  <div class="col-12">
    <div class="d-flex flex-column flex-xl-row justify-content-between gap-4 align-items-start">
      <div>
        <h3 class="allowance-panel-title">Detail Uang Saku Intern</h3>
        <p class="allowance-soft-text mb-0">Tinjau identitas intern, rumus perhitungan, dan detail kehadiran sebelum rekap dicetak.</p>
        <small class="allowance-soft-text d-block mt-2">Periode {{ $monthLabel }}</small>
      </div>
      <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.allowances.index', ['month' => $selectedMonth]) }}" class="btn btn-outline-secondary">Kembali</a>
        <a href="{{ route('admin.allowances.show.print', ['intern' => $intern, 'month' => $selectedMonth]) }}" target="_blank" class="btn btn-primary">Cetak PDF</a>
      </div>
    </div>
  </div>

  <div class="col-xl-5">
    <div class="card allowance-hero-card allowance-profile-card">
      <span class="allowance-profile-badge">Detail Uang Saku Intern</span>
      <div class="allowance-profile-grid">
        <div class="allowance-profile-row">
          <div class="allowance-profile-label">Nama</div>
          <div class="allowance-profile-value">{{ $intern->name }}</div>
        </div>
        <div class="allowance-profile-row">
          <div class="allowance-profile-label">Divisi</div>
          <div class="allowance-profile-value">{{ $intern->division->name ?? '-' }}</div>
        </div>
        <div class="allowance-profile-row">
          <div class="allowance-profile-label">Asal Sekolah/Kampus</div>
          <div class="allowance-profile-value">{{ $allowance['institution_label'] }}</div>
        </div>
        <div class="allowance-profile-row">
          <div class="allowance-profile-label">Periode</div>
          <div class="allowance-profile-value text-primary">{{ $monthLabel }}</div>
        </div>
        <div class="allowance-profile-row">
          <div class="allowance-profile-label">{{ $allowance['identifier_label'] }}</div>
          <div class="allowance-profile-value">{{ $allowance['identifier_value'] }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-7">
    <div class="allowance-kpi-grid">
      <div class="card allowance-summary-card allowance-kpi-card">
        <div class="allowance-kpi-icon primary">
          <i class="ri ri-calendar-check-line"></i>
        </div>
        <div class="allowance-kpi-label">Kehadiran Tercatat</div>
        <div class="allowance-kpi-value">{{ $allowance['attendance_days'] }} <span style="font-size:1.1rem; font-weight:600;">Hari</span></div>
        <div class="allowance-kpi-meta">Hadir {{ $allowance['present_days'] }} • Terlambat {{ $allowance['late_days'] }}</div>
      </div>

      <div class="card allowance-summary-card allowance-kpi-card">
        <div class="allowance-kpi-icon success">
          <i class="ri ri-money-dollar-box-line"></i>
        </div>
        <div class="allowance-kpi-label">Tarif Harian</div>
        <div class="allowance-kpi-value" style="font-size:1.85rem;">{{ $allowance['daily_rate_label'] }}</div>
        <div class="allowance-kpi-meta">Berdasarkan rumus tetap Rp 500.000 / 22 hari kerja.</div>
      </div>

      <div class="card allowance-summary-card allowance-kpi-card">
        <div class="allowance-kpi-icon warning">
          <i class="ri ri-timer-line"></i>
        </div>
        <div class="allowance-kpi-label">Hari Dibayar</div>
        <div class="allowance-kpi-value">{{ $allowance['counted_days'] }} <span style="font-size:1.1rem; font-weight:600;">/ {{ $allowance['max_workdays'] }}</span></div>
        <div class="allowance-kpi-meta">{{ $allowance['is_capped'] ? 'Masuk batas maksimal bulanan.' : 'Masih di bawah batas maksimum.' }}</div>
      </div>

      <div class="card allowance-kpi-card featured">
        <div class="allowance-kpi-icon">
          <i class="ri ri-wallet-3-line"></i>
        </div>
        <div class="allowance-kpi-label">Total Uang Saku</div>
        <div class="allowance-kpi-value">{{ $allowance['allowance_amount_label'] }}</div>
        <div class="allowance-kpi-meta">Nominal akhir untuk periode {{ $monthLabel }}.</div>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card allowance-calculation-card">
      <div class="allowance-section-caption">Rincian Perhitungan</div>
      <div class="allowance-calculation-expression">
        <span>{{ $allowance['max_amount_label'] }}</span>
        <span>/</span>
        <span>{{ $allowance['max_workdays'] }} Hari</span>
        <span>×</span>
        <span>{{ $allowance['counted_days'] }} Hari</span>
        <span>=</span>
        <span>{{ $allowance['allowance_amount_label'] }}</span>
      </div>
      <div class="allowance-calculation-muted">
        Kehadiran yang dihitung hanya status hadir dan terlambat. Jika jumlah hadir melebihi {{ $allowance['max_workdays'] }} hari kerja, nominal otomatis dipatok maksimal.
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card allowance-history-card">
      <div class="allowance-history-head">
        <div>
          <h4 class="mb-1">Detail Kehadiran Harian</h4>
          <p class="allowance-soft-text mb-0">Riwayat kehadiran yang digunakan untuk menghitung uang saku periode {{ $monthLabel }}.</p>
        </div>
        <div>
          <span class="allowance-late-pill">{{ $lateEntries }} Terlambat</span>
        </div>
      </div>
      <div class="table-responsive ims-card-table-wrap">
        <table class="table align-middle ims-card-table">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Status</th>
              <th>Check In</th>
              <th>Check Out</th>
              <th>Lokasi</th>
              <th>Keterlambatan</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($allowance['attendances'] as $attendance)
              <tr>
                <td data-label="Tanggal" class="ims-card-primary">{{ $attendance->date->translatedFormat('d M Y') }}</td>
                <td data-label="Status"><span class="allowance-status-pill {{ $attendance->status_badge_class }}">{{ $attendance->status_label }}</span></td>
                <td data-label="Check In" class="allowance-history-metric">{{ $attendance->check_in_at?->format('H:i') ?? '-' }}</td>
                <td data-label="Check Out" class="allowance-history-metric">{{ $attendance->check_out_at?->format('H:i') ?? '-' }}</td>
                <td data-label="Lokasi">{{ $attendance->attendanceLocation?->name ?? '-' }}</td>
                <td data-label="Keterlambatan">
                  <div class="allowance-history-metric">{{ $attendance->late_duration_label }}</div>
                  @if ($attendance->status === \App\Models\Attendance::STATUS_LATE)
                    <small class="allowance-soft-text">Over threshold</small>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center py-4 allowance-soft-text">Belum ada data absensi pada periode ini.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card allowance-footer-card">
      <div class="allowance-footer-meta">
        <div>
          <i class="ri ri-settings-3-line me-2"></i>
          System Generated Document — ID: {{ $documentId }}
        </div>
        <div>
          Generated on: {{ now()->locale('id')->translatedFormat('d F Y') }} • {{ now()->format('H:i') }} WIB
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
