<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Cetak Detail Uang Saku Intern</title>
  <style>
    body { font-family: Arial, sans-serif; color: #18213f; margin: 28px; font-size: 13px; }
    h1,h2,h3,p { margin: 0; }
    .muted { color: #66708f; }
    .header { display: flex; justify-content: space-between; align-items: flex-start; gap: 24px; margin-bottom: 20px; }
    .brand { max-width: 70%; }
    .brand h1 { font-size: 24px; margin-top: 4px; margin-bottom: 6px; }
    .brand-line { width: 84px; height: 4px; background: #3242d3; border-radius: 999px; margin: 8px 0 12px; }
    .company { font-size: 14px; font-weight: 700; color: #0f172a; }
    .print-meta { text-align: right; font-size: 12px; }
    .section-card { border: 1px solid #d9deef; border-radius: 14px; padding: 14px 16px; margin-top: 14px; }
    .identity-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px 20px; }
    .identity-label { display: block; font-size: 11px; text-transform: uppercase; letter-spacing: .06em; color: #7a839f; margin-bottom: 4px; }
    .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-top: 16px; }
    .summary-card { border: 1px solid #d9deef; border-radius: 14px; padding: 14px; min-height: 98px; }
    .summary-title { font-size: 11px; text-transform: uppercase; letter-spacing: .05em; color: #6b7391; margin-bottom: 8px; }
    .summary-value { font-size: 28px; font-weight: 800; color: #141d39; line-height: 1.05; }
    .summary-value.money { color: #3242d3; font-size: 30px; }
    .summary-note { margin-top: 6px; color: #66708f; font-size: 12px; line-height: 1.45; }
    .formula-box { margin-top: 16px; padding: 16px; border: 1px solid #d9deef; border-radius: 14px; background: #f7f9ff; }
    .formula-title { font-size: 11px; text-transform: uppercase; letter-spacing: .06em; color: #6b7391; margin-bottom: 10px; }
    .formula-value { font-size: 20px; font-weight: 800; color: #19234a; }
    .table-title { margin-top: 20px; margin-bottom: 10px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #d9deef; padding: 11px 12px; text-align: left; vertical-align: top; }
    th { background: #eef2ff; font-size: 11px; text-transform: uppercase; letter-spacing: .05em; color: #4b5677; }
    .text-center { text-align: center; }
    .status-pill { display: inline-block; padding: 4px 9px; border-radius: 999px; font-size: 11px; font-weight: 700; }
    .status-hadir { background: #e9f9ef; color: #117a38; }
    .status-terlambat { background: #fff3dd; color: #b56a00; }
    .status-izin { background: #e6f7ff; color: #0b7ea7; }
    .status-sakit { background: #ffe9e9; color: #c73939; }
    .status-tidak_hadir { background: #eceff5; color: #69748d; }
    .footer-note { margin-top: 22px; font-size: 12px; color: #66708f; }
    .signature-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 32px; margin-top: 28px; }
    .signature-box { text-align: center; }
    .signature-space { height: 56px; }
    .actions { margin-bottom: 14px; }
    .actions button { border: 1px solid #3242d3; background: #3242d3; color: #fff; border-radius: 10px; padding: 8px 14px; cursor: pointer; }
    @media print {
      body { margin: 18px; }
      .actions { display: none; }
    }
  </style>
</head>
<body>
@php
  $intern = $allowance['intern'];
  $monthLabel = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->locale('id')->translatedFormat('F Y');
  $statusClassMap = [
      'hadir' => 'status-hadir',
      'terlambat' => 'status-terlambat',
      'izin' => 'status-izin',
      'sakit' => 'status-sakit',
      'tidak_hadir' => 'status-tidak_hadir',
  ];
@endphp

  <div class="actions">
    <button type="button" onclick="window.print()">Cetak / Simpan PDF</button>
  </div>

  <div class="header">
    <div class="brand">
      <div class="company">{{ config('allowance.company_name') }}</div>
      <div class="brand-line"></div>
      <h1>Detail Uang Saku Intern</h1>
      <p class="muted">Rekap resmi uang saku intern untuk periode {{ $monthLabel }}.</p>
    </div>
    <div class="print-meta">
      <div><strong>Tanggal Cetak</strong></div>
      <div class="muted">{{ now()->locale('id')->translatedFormat('d F Y H:i') }}</div>
      <div style="margin-top: 10px;"><strong>Periode</strong></div>
      <div class="muted">{{ $monthLabel }}</div>
    </div>
  </div>

  <div class="section-card">
    <div class="identity-grid">
      <div>
        <span class="identity-label">Nama Intern</span>
        <strong>{{ $intern->name }}</strong>
      </div>
      <div>
        <span class="identity-label">Divisi Penempatan</span>
        <strong>{{ $intern->division->name ?? '-' }}</strong>
      </div>
      <div>
        <span class="identity-label">Asal Sekolah/Kampus</span>
        <strong>{{ $allowance['institution_label'] }}</strong>
      </div>
      <div>
        <span class="identity-label">{{ $allowance['identifier_label'] }}</span>
        <strong>{{ $allowance['identifier_value'] }}</strong>
      </div>
    </div>
  </div>

  <div class="summary-grid">
    <div class="summary-card">
      <div class="summary-title">Kehadiran Tercatat</div>
      <div class="summary-value">{{ $allowance['attendance_days'] }} hari</div>
      <div class="summary-note">Hadir {{ $allowance['present_days'] }} • Terlambat {{ $allowance['late_days'] }}</div>
    </div>
    <div class="summary-card">
      <div class="summary-title">Hari Dibayar</div>
      <div class="summary-value">{{ $allowance['counted_days'] }}/{{ $allowance['max_workdays'] }}</div>
      <div class="summary-note">{{ $allowance['is_capped'] ? 'Mencapai batas maksimum bulanan.' : 'Masih di bawah batas maksimum.' }}</div>
    </div>
    <div class="summary-card">
      <div class="summary-title">Tarif Harian</div>
      <div class="summary-value" style="font-size: 24px;">{{ $allowance['daily_rate_label'] }}</div>
      <div class="summary-note">Rumus tetap Rp 500.000 / 22 hari kerja.</div>
    </div>
    <div class="summary-card">
      <div class="summary-title">Total Uang Saku</div>
      <div class="summary-value money">{{ $allowance['allowance_amount_label'] }}</div>
      <div class="summary-note">Maksimal {{ $allowance['max_amount_label'] }} per orang.</div>
    </div>
  </div>

  <div class="formula-box">
    <div class="formula-title">Rumus Perhitungan</div>
    <div class="formula-value">{{ $allowance['max_amount_label'] }} / {{ $allowance['max_workdays'] }} x {{ $allowance['counted_days'] }} hari = {{ $allowance['allowance_amount_label'] }}</div>
    <div class="summary-note" style="margin-top: 8px;">Perhitungan hanya memakai status hadir dan terlambat. Jika kehadiran melewati {{ $allowance['max_workdays'] }} hari, nominal dipatok pada batas maksimum.</div>
  </div>

  <div class="table-title">
    <h3>Riwayat Absensi Bulan {{ $monthLabel }}</h3>
    <p class="muted">Tabel berikut menjadi dasar perhitungan uang saku intern pada periode berjalan.</p>
  </div>

  <table>
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
          <td>{{ $attendance->date->translatedFormat('d M Y') }}</td>
          <td>
            <span class="status-pill {{ $statusClassMap[$attendance->status] ?? 'status-tidak_hadir' }}">
              {{ $attendance->status_label }}
            </span>
          </td>
          <td class="text-center">{{ $attendance->check_in_at?->format('H:i') ?? '-' }}</td>
          <td class="text-center">{{ $attendance->check_out_at?->format('H:i') ?? '-' }}</td>
          <td>{{ $attendance->attendanceLocation?->name ?? '-' }}</td>
          <td>{{ $attendance->late_duration_label }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="muted">Belum ada data absensi pada periode ini.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="footer-note">
    Dokumen ini dicetak dari sistem dan digunakan sebagai rekap administrasi uang saku intern.
  </div>

  <div class="signature-grid">
    <div class="signature-box">
      <div class="muted">Mengetahui,</div>
      <div class="signature-space"></div>
      <strong>Admin IMS</strong>
    </div>
    <div class="signature-box">
      <div class="muted">Diperiksa oleh,</div>
      <div class="signature-space"></div>
      <strong>Pembina / PIC</strong>
    </div>
  </div>

  <script>
    window.addEventListener('load', () => {
      setTimeout(() => window.print(), 120);
    });
  </script>
</body>
</html>
