<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Perhitungan Uang Saku Intern</title>
  <style>
    :root {
      --text: #172033;
      --muted: #5f6b85;
      --line: #24324a;
      --line-soft: #ccd6e7;
      --primary: #1f3b73;
      --primary-soft: #f5f8fc;
      --primary-border: #d7e1ee;
      --head-bg: #edf2f8;
      --summary-bg: #fafbfd;
      --total-bg: #f3f6fb;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 24px;
      font-family: Arial, sans-serif;
      color: var(--text);
      background: #fff;
      font-size: 12px;
      line-height: 1.45;
    }

    h1, h2, h3, p {
      margin: 0;
    }

    .actions {
      margin-bottom: 14px;
    }

    .actions button {
      border: 1px solid #1f2937;
      background: #1f2937;
      color: #fff;
      border-radius: 8px;
      padding: 8px 14px;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
    }

    .document {
      border: 1px solid var(--line-soft);
      padding: 24px 24px 18px;
      min-height: calc(100vh - 48px);
      display: flex;
      flex-direction: column;
      background: #fff;
    }

    .document-head {
      border-bottom: 1px solid var(--primary-border);
      padding-bottom: 14px;
      margin-bottom: 16px;
    }

    .document-head-top {
      display: flex;
      justify-content: space-between;
      gap: 16px;
      align-items: flex-start;
      margin-bottom: 10px;
    }

    .company-block h3 {
      font-size: 16px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: var(--primary);
    }

    .company-block p {
      color: var(--muted);
      margin-top: 4px;
    }

    .meta-block {
      text-align: right;
      font-size: 11px;
      color: var(--muted);
    }

    .document-code {
      display: inline-block;
      margin-bottom: 6px;
      padding: 4px 10px;
      border: 1px solid var(--primary-border);
      background: #fff;
      color: var(--primary);
      border-radius: 999px;
      font-weight: 700;
    }

    .title-block {
      text-align: center;
    }

    .title-block h1 {
      font-size: 24px;
      line-height: 1.2;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.02em;
      color: var(--primary);
    }

    .title-block h2 {
      margin-top: 6px;
      font-size: 18px;
      font-weight: 800;
      text-transform: uppercase;
      color: var(--primary);
    }

    .title-note {
      margin-top: 6px;
      font-size: 11px;
      color: var(--muted);
    }

    .summary-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 18px;
    }

    .summary-table td {
      width: 25%;
      border: 1px solid var(--line-soft);
      background: var(--summary-bg);
      border-top: 2px solid var(--primary-border);
      padding: 10px 12px;
      vertical-align: top;
    }

    .summary-label {
      display: block;
      font-size: 10px;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 4px;
    }

    .summary-value {
      display: block;
      font-size: 18px;
      font-weight: 800;
      color: var(--primary);
    }

    .summary-meta {
      display: block;
      margin-top: 3px;
      font-size: 11px;
      color: var(--muted);
    }

    table.report-table {
      width: 100%;
      border-collapse: collapse;
    }

    .report-table th,
    .report-table td {
      border: 1px solid var(--line);
      padding: 8px 7px;
      vertical-align: middle;
      color: #000;
    }

    .report-table th {
      background: var(--head-bg);
      text-align: center;
      font-size: 11px;
      font-weight: 700;
      line-height: 1.35;
      color: #000;
    }

    .report-table td {
      font-size: 12px;
      text-align: center;
      background: #fff;
    }

    .report-table tbody tr:nth-child(even) td {
      background: #f6f8fb;
    }

    .text-left {
      text-align: left !important;
    }

    .text-right {
      text-align: right !important;
    }

    .name-cell strong {
      display: block;
      font-size: 12px;
      color: #000;
    }

    .name-cell small {
      display: block;
      margin-top: 2px;
      color: #000;
      font-size: 10px;
    }

    .amount-cell strong {
      font-size: 12px;
      color: #000;
    }

    .total-row td {
      background: var(--total-bg);
      font-weight: 800;
      font-size: 13px;
      color: #000;
    }

    .notes {
      margin-top: 12px;
      padding: 10px 12px;
      border: 1px solid var(--primary-border);
      background: var(--primary-soft);
      font-size: 11px;
      color: var(--muted);
    }

    .approval-section {
      display: flex;
      justify-content: flex-end;
      margin-top: 26px;
      align-items: flex-start;
    }

    .signature-box {
      width: 32%;
      text-align: center;
    }

    .signature-date {
      text-align: right;
      margin-bottom: 56px;
    }

    .signature-role {
      margin-bottom: 58px;
    }

    .signature-name {
      display: inline-block;
      min-width: 220px;
      border-top: 1px solid var(--line);
      padding-top: 8px;
      font-weight: 800;
    }

    .signature-title {
      margin-top: 4px;
      color: var(--muted);
    }

    .document-bottom {
      margin-top: auto;
      padding-top: 18px;
      border-top: 1px solid var(--primary-border);
      display: flex;
      justify-content: space-between;
      gap: 14px;
      font-size: 11px;
      color: var(--muted);
    }

    @media print {
      body {
        padding: 12px;
      }

      .actions {
        display: none;
      }

      .document {
        border: 0;
        padding: 0;
        min-height: 270mm;
      }
    }
  </style>
</head>
<body>
@php
  $month = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->locale('id');
  $periodLabel = $month->translatedFormat('F Y');
  $documentCode = sprintf('IMS-%s-%03d', str_replace('-', '', $selectedMonth), $rows->count());
  $totalAmount = $rows->sum('allowance_amount');
  $totalAttendance = $rows->sum('attendance_days');
  $generatedAt = now();
  $filterDescriptions = [];

  if (! empty($selectedFilters['search'])) {
    $filterDescriptions[] = 'Pencarian: '.$selectedFilters['search'];
  }

  if (! empty($selectedFilters['type'])) {
    $filterDescriptions[] = 'Tipe: '.($filterOptions['types'][$selectedFilters['type']] ?? $selectedFilters['type']);
  }

  if (! empty($selectedFilters['institution_id'])) {
    $selectedInstitution = $filterOptions['institutions']->firstWhere('id', (int) $selectedFilters['institution_id']);
    $filterDescriptions[] = 'Asal Sekolah/Kampus: '.($selectedInstitution?->name ?? $selectedFilters['institution_id']);
  }

  if (! empty($selectedFilters['division_id'])) {
    $selectedDivision = $filterOptions['divisions']->firstWhere('id', (int) $selectedFilters['division_id']);
    $filterDescriptions[] = 'Divisi: '.(($selectedDivision?->code ? $selectedDivision->code.' - ' : '').($selectedDivision?->name ?? $selectedFilters['division_id']));
  }
@endphp

  <div class="actions">
    <button type="button" onclick="window.print()">Cetak / Simpan PDF</button>
  </div>

  <div class="document">
    <div class="document-head">
      <div class="document-head-top">
        <div class="company-block">
          <h3>{{ config('allowance.company_name') }}</h3>
          <p>Dokumen Rekap Uang Saku Intern Magang</p>
        </div>
        <div class="meta-block">
          <div class="document-code">ID Dokumen: {{ $documentCode }}</div>
          <div>Dicetak: {{ $generatedAt->locale('id')->translatedFormat('d F Y H:i') }} WIB</div>
        </div>
      </div>

      <div class="title-block">
        <h1>Perhitungan Uang Saku Intern Berdasarkan Absensi</h1>
        <h2>Periode {{ strtoupper($periodLabel) }}</h2>
        <div class="title-note">
          Rekap ini digunakan sebagai dasar administrasi penyaluran uang saku intern.
          @if ($filterDescriptions !== [])
            <br>Filter aktif: {{ implode(' • ', $filterDescriptions) }}.
          @endif
        </div>
      </div>
    </div>

    <table class="summary-table">
      <tr>
        <td>
          <span class="summary-label">Intern Tersaring</span>
          <span class="summary-value">{{ $rows->count() }}</span>
          <span class="summary-meta">Jumlah intern sesuai filter aktif.</span>
        </td>
        <td>
          <span class="summary-label">Hari Hadir</span>
          <span class="summary-value">{{ number_format($totalAttendance, 0, ',', '.') }} Hari</span>
          <span class="summary-meta">Akumulasi hadir dan terlambat bulan ini.</span>
        </td>
        <td>
          <span class="summary-label">Total Uang Saku</span>
          <span class="summary-value">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
          <span class="summary-meta">Akumulasi nominal dari data yang tampil.</span>
        </td>
      </tr>
    </table>

    <table class="report-table">
      <thead>
        <tr>
          <th style="width: 44px;">No</th>
          <th class="text-left" style="width: 220px;">Nama Intern</th>
          <th class="text-left" style="width: 170px;">Asal Sekolah/Kampus</th>
          <th class="text-left" style="width: 170px;">Unit Penempatan</th>
          <th style="width: 86px;">1 (Satu) Bulan</th>
          <th style="width: 110px;">UT/Bulan (Rupiah)</th>
          <th style="width: 120px;">Jumlah Kehadiran (Absensi)</th>
          <th style="width: 130px;">Jumlah Penerimaan</th>
          <th style="width: 130px;">No Rekening</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($rows->values() as $index => $row)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td class="text-left name-cell">
              <strong>{{ $row['intern']->name }}</strong>
              <small>{{ $row['identifier_label'] }}: {{ $row['identifier_value'] }}</small>
              <small>{{ $row['participant_type_label'] }}</small>
            </td>
            <td class="text-left">{{ $row['institution_label'] }}</td>
            <td class="text-left">{{ $row['intern']->division->name ?? '-' }}</td>
            <td>{{ $row['max_workdays'] }}</td>
            <td class="text-right">{{ number_format(config('allowance.max_amount', 500000), 0, ',', '.') }}</td>
            <td>
              {{ $row['attendance_days'] }}
              <div style="margin-top: 2px; color: var(--muted); font-size: 10px;">
                Hadir {{ $row['present_days'] }} | Terlambat {{ $row['late_days'] }}
              </div>
            </td>
            <td class="text-right amount-cell">
              <strong>{{ number_format($row['allowance_amount'], 0, ',', '.') }}</strong>
            </td>
            <td>{{ $row['intern']->bank_account_number ?: '-' }}</td>
          </tr>
        @empty
          <tr>
          <td colspan="9" style="padding: 18px; color: var(--muted);">Tidak ada intern yang masuk rekap pada periode ini.</td>
          </tr>
        @endforelse
        @if ($rows->isNotEmpty())
          <tr class="total-row">
            <td colspan="7">Total</td>
            <td class="text-right">{{ number_format($totalAmount, 0, ',', '.') }}</td>
            <td></td>
          </tr>
        @endif
      </tbody>
    </table>

    <div class="notes">
      Catatan: perhitungan uang saku menggunakan rumus Rp {{ number_format(config('allowance.max_amount', 500000), 0, ',', '.') }} / {{ config('allowance.max_workdays', 22) }} x jumlah kehadiran intern. Jumlah kehadiran yang dihitung mencakup status hadir dan terlambat, dengan batas maksimum {{ config('allowance.max_workdays', 22) }} hari per intern. Total kehadiran yang tercatat pada rekap ini adalah {{ number_format($totalAttendance, 0, ',', '.') }} hari.
    </div>

    <div class="approval-section">
      <div class="signature-box">
        <div class="signature-date">Batam, {{ $generatedAt->locale('id')->translatedFormat('d F Y') }}</div>
        <div class="signature-role">Menyetujui,</div>
        <div class="signature-name">Djumadi</div>
        <div class="signature-title">GM. SDM &amp; Umum</div>
      </div>
    </div>

    <div class="document-bottom">
      <div>{{ config('allowance.company_name') }} &bull; Dokumen Internal</div>
      <div>Halaman rekap uang saku intern</div>
    </div>
  </div>

  <script>
    window.addEventListener('load', () => {
      setTimeout(() => window.print(), 120);
    });
  </script>
</body>
</html>
