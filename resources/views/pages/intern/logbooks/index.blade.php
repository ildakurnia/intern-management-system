@extends('layouts/contentNavbarLayout')

@section('title', 'Logbook Saya')

@php
  $monthStart = $calendarMonth->copy()->startOfMonth();
  $monthEnd = $calendarMonth->copy()->endOfMonth();
  $calendarStart = $monthStart->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
  $calendarEnd = $monthEnd->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
  $calendarDays = [];

  for ($date = $calendarStart->copy(); $date->lte($calendarEnd); $date->addDay()) {
      $calendarDays[] = $date->copy();
  }

  $calendarWeeks = array_chunk($calendarDays, 7);
  $weekdayLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
  $logbooksByDate = $logbooks->keyBy(fn ($logbook) => $logbook->tanggal->format('Y-m-d'));
  $currentMonthParam = $calendarMonth->format('Y-m');
  $previousMonthParam = $calendarMonth->copy()->subMonth()->format('Y-m');
  $nextMonthParam = $calendarMonth->copy()->addMonth()->format('Y-m');
  $todayString = now()->format('Y-m-d');
  $startDateString = $intern?->start_date?->toDateString();
  $endDateString = $intern?->end_date?->toDateString();
  $canGoPreviousMonth = ! $startDateString || $calendarMonth->gt($intern->start_date->copy()->startOfMonth());
  $canGoNextMonth = $calendarMonth->lt(now()->startOfMonth());
  $calendarLogbooks = $logbooksByDate
      ->mapWithKeys(fn ($logbook, $date) => [
          $date => [
              'id' => $logbook->id,
              'tanggal' => $logbook->tanggal->format('Y-m-d'),
              'uraian_aktivitas' => $logbook->uraian_aktivitas,
              'pembelajaran_diperoleh' => $logbook->pembelajaran_diperoleh,
              'kendala_dialami' => $logbook->kendala_dialami,
          ],
      ])
      ->toArray();
  $oldCalendarFormState = [
      'logbook_id' => old('logbook_id'),
      'tanggal' => old('tanggal'),
      'uraian_aktivitas' => old('uraian_aktivitas'),
      'pembelajaran_diperoleh' => old('pembelajaran_diperoleh'),
      'kendala_dialami' => old('kendala_dialami'),
  ];
  $totalLogbooks = $logbooks->count();
@endphp

@section('page-style')
  <style>
    .intern-logbook-page {
      --intern-logbook-card-bg: var(--bs-card-bg);
      --intern-logbook-card-bg-soft: var(--bs-body-bg);
      --intern-logbook-border: var(--bs-border-color);
      --intern-logbook-border-strong: rgba(var(--bs-primary-rgb), 0.16);
      --intern-logbook-title: var(--bs-heading-color);
      --intern-logbook-text: var(--bs-body-color);
      --intern-logbook-soft: var(--bs-secondary-color);
      --intern-logbook-muted: var(--bs-tertiary-color);
      --intern-logbook-hover: rgba(var(--bs-primary-rgb), 0.08);
      --intern-logbook-primary: var(--bs-primary);
      --intern-logbook-success: var(--bs-success);
      --intern-logbook-future: var(--bs-secondary-color);
      --intern-logbook-shadow: 0 16px 42px rgba(47, 43, 61, 0.08);
    }

    html[data-bs-theme="dark"] .intern-logbook-page {
      --intern-logbook-card-bg-soft: rgba(31, 33, 48, 0.8);
      --intern-logbook-border: rgba(219, 223, 255, 0.12);
      --intern-logbook-border-strong: rgba(93, 91, 255, 0.22);
      --intern-logbook-title: #f4f5ff;
      --intern-logbook-text: #dde1f5;
      --intern-logbook-soft: #aab1cd;
      --intern-logbook-muted: #8f97b4;
      --intern-logbook-hover: rgba(93, 91, 255, 0.16);
      --intern-logbook-shadow: 0 18px 45px rgba(0, 0, 0, 0.28);
    }

    .intern-logbook-page {
      display: grid;
      gap: 1rem;
    }

    .intern-logbook-card {
      border: 1px solid var(--intern-logbook-border);
      border-radius: 1.6rem;
      background: var(--intern-logbook-card-bg);
      box-shadow: var(--intern-logbook-shadow);
      color: var(--intern-logbook-text);
    }

    .intern-logbook-header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 1rem;
      padding: 1.15rem 1.2rem 0;
    }

    .intern-logbook-title {
      margin: 0;
      color: var(--intern-logbook-title);
      font-size: 1.22rem;
      font-weight: 800;
      letter-spacing: -0.03em;
    }

    .intern-logbook-subtitle {
      margin: 0.45rem 0 0;
      color: var(--intern-logbook-soft);
      font-size: 0.92rem;
      line-height: 1.6;
    }

    .intern-logbook-total {
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      padding: 0.7rem 0.9rem;
      border-radius: 999px;
      background: rgba(var(--bs-primary-rgb), 0.08);
      color: var(--bs-primary);
      font-size: 0.86rem;
      font-weight: 700;
      white-space: nowrap;
    }

    .intern-logbook-calendar-wrap {
      padding: 0.95rem 1.2rem 1.2rem;
    }

    .intern-logbook-calendar-board {
      overflow-x: auto;
      padding-bottom: 0.35rem;
      -webkit-overflow-scrolling: touch;
    }

    .intern-logbook-calendar-grid {
      min-width: 52rem;
    }

    .intern-logbook-calendar-toolbar {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.75rem;
      margin-bottom: 1rem;
    }

    .intern-logbook-month-button {
      width: 2.55rem;
      height: 2.55rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border: 1px solid var(--intern-logbook-border);
      border-radius: 0.85rem;
      color: var(--intern-logbook-title);
      background: var(--intern-logbook-card-bg);
      text-decoration: none;
      transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .intern-logbook-month-button:hover {
      transform: translateY(-1px);
      border-color: var(--intern-logbook-border-strong);
      box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
      color: var(--intern-logbook-primary);
    }

    .intern-logbook-month-label {
      min-width: 10rem;
      text-align: center;
      color: var(--intern-logbook-title);
      font-size: 1.12rem;
      font-weight: 700;
      letter-spacing: -0.02em;
    }

    .intern-logbook-weekdays,
    .intern-logbook-week {
      display: grid;
      grid-template-columns: repeat(7, minmax(0, 1fr));
    }

    .intern-logbook-weekdays {
      margin-bottom: 0.35rem;
      padding-inline: 0.15rem;
    }

    .intern-logbook-weekday {
      padding: 0.45rem 0.35rem;
      color: var(--intern-logbook-soft);
      font-size: 0.82rem;
      font-weight: 600;
      text-align: center;
      letter-spacing: 0.01em;
    }

    .intern-logbook-week {
      border-top: 1px solid var(--intern-logbook-border);
    }

    .intern-logbook-day {
      min-height: 5.45rem;
      padding: 0.3rem;
      background: var(--intern-logbook-card-bg-soft);
      border-right: 1px solid var(--intern-logbook-border);
    }

    .intern-logbook-week .intern-logbook-day:last-child {
      border-right: 0;
    }

    .intern-logbook-day-button {
      width: 100%;
      height: 100%;
      min-height: 4.75rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 0.3rem;
      border: 0;
      border-radius: 0.85rem;
      background: transparent;
      color: var(--intern-logbook-soft);
      transition: background 0.18s ease, transform 0.18s ease, color 0.18s ease;
    }

    .intern-logbook-day-button:hover:not(:disabled) {
      background: var(--intern-logbook-hover);
      color: var(--intern-logbook-primary);
      transform: translateY(-1px);
    }

    .intern-logbook-day-button:disabled {
      cursor: not-allowed;
      opacity: 0.72;
    }

    .intern-logbook-day-number {
      width: 2rem;
      height: 2rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      font-size: 0.95rem;
      font-weight: 600;
      line-height: 1;
    }

    .intern-logbook-day.is-today .intern-logbook-day-number {
      background: var(--intern-logbook-primary);
      color: #fff;
    }

    .intern-logbook-day.is-filled .intern-logbook-day-button {
      color: var(--intern-logbook-success);
    }

    .intern-logbook-day.is-filled .intern-logbook-day-button:hover {
      background: rgba(var(--bs-success-rgb), 0.1);
      color: var(--intern-logbook-success);
    }

    .intern-logbook-day-dot {
      width: 0.5rem;
      height: 0.5rem;
      border-radius: 50%;
      border: 2px solid rgba(148, 163, 184, 0.48);
      background: transparent;
    }

    .intern-logbook-day.is-filled .intern-logbook-day-dot {
      border-color: var(--intern-logbook-success);
      background: var(--intern-logbook-success);
    }

    .intern-logbook-day.is-today .intern-logbook-day-dot {
      border-color: var(--intern-logbook-primary);
      background: var(--intern-logbook-primary);
    }

    .intern-logbook-day.is-outside {
      background: rgba(var(--bs-secondary-rgb), 0.05);
    }

    .intern-logbook-day.is-outside .intern-logbook-day-button {
      color: var(--intern-logbook-muted);
    }

    .intern-logbook-day.is-future .intern-logbook-day-button {
      color: var(--intern-logbook-future);
    }

    .intern-logbook-day.is-disabled-range {
      background: rgba(var(--bs-secondary-rgb), 0.03);
    }

    .intern-logbook-day.is-disabled-range .intern-logbook-day-button {
      cursor: not-allowed;
      opacity: 0.42;
    }

    .intern-logbook-legend {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem 1rem;
      margin-top: 0.9rem;
      color: var(--intern-logbook-soft);
      font-size: 0.82rem;
    }

    .intern-logbook-legend span {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
    }

    .intern-logbook-mobile-hint {
      display: none;
      margin-bottom: 0.9rem;
      color: var(--intern-logbook-soft);
      font-size: 0.82rem;
      line-height: 1.5;
    }

    .intern-logbook-period-note {
      margin: 0 0 0.9rem;
      color: var(--intern-logbook-soft);
      font-size: 0.82rem;
      line-height: 1.5;
    }

    .intern-logbook-modal .modal-content {
      border: 0;
      border-radius: 1.35rem;
      background-color: var(--bs-body-bg);
      color: var(--bs-body-color);
      box-shadow: 0 24px 54px rgba(15, 23, 42, 0.18);
      color-scheme: light;
    }

    .intern-logbook-modal .modal-header,
    .intern-logbook-modal .modal-footer {
      border: 0;
      background-color: var(--bs-body-bg);
    }

    .intern-logbook-modal .modal-body {
      padding-top: 0;
      background-color: var(--bs-body-bg);
    }

    .intern-logbook-modal .form-control,
    .intern-logbook-modal .form-select {
      background-color: var(--bs-body-bg);
      border-color: var(--intern-logbook-border);
      color: var(--bs-body-color);
      box-shadow: none;
    }

    .intern-logbook-modal .form-control::placeholder {
      color: var(--bs-secondary-color);
    }

    .intern-logbook-modal .form-control:focus,
    .intern-logbook-modal .form-select:focus {
      background-color: var(--bs-body-bg);
      border-color: var(--intern-logbook-border-strong);
      color: var(--bs-body-color);
      box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.12);
    }

    .intern-logbook-modal .form-control[readonly],
    .intern-logbook-modal .form-control:disabled,
    .intern-logbook-modal .form-select:disabled {
      background-color: rgba(var(--bs-secondary-rgb), 0.08);
      color: var(--intern-logbook-muted);
    }

    html[data-bs-theme="dark"] .intern-logbook-modal .form-control,
    html[data-bs-theme="dark"] .intern-logbook-modal .form-select {
      background-color: var(--bs-body-bg);
      border-color: rgba(219, 223, 255, 0.14);
      color: var(--intern-logbook-title);
    }

    html[data-bs-theme="dark"] .intern-logbook-modal .form-control::placeholder {
      color: rgba(170, 177, 205, 0.78);
    }

    html[data-bs-theme="dark"] .intern-logbook-modal .form-control:focus,
    html[data-bs-theme="dark"] .intern-logbook-modal .form-select:focus {
      background-color: var(--bs-body-bg);
      border-color: rgba(93, 91, 255, 0.45);
      box-shadow: 0 0 0 0.2rem rgba(93, 91, 255, 0.14);
    }

    html[data-bs-theme="dark"] .intern-logbook-modal .modal-content,
    html[data-bs-theme="dark"] .intern-logbook-modal .modal-header,
    html[data-bs-theme="dark"] .intern-logbook-modal .modal-body,
    html[data-bs-theme="dark"] .intern-logbook-modal .modal-footer {
      background-color: var(--bs-body-bg);
      color-scheme: dark;
    }

    .intern-logbook-form-label {
      color: var(--intern-logbook-title);
      font-size: 0.95rem;
      font-weight: 600;
    }

    .intern-logbook-form-note {
      margin-top: 0.35rem;
      color: var(--bs-danger);
      font-size: 0.82rem;
    }

    .intern-logbook-check {
      display: flex;
      align-items: flex-start;
      gap: 0.7rem;
      margin-top: 0.3rem;
    }

    .intern-logbook-check input {
      margin-top: 0.2rem;
    }

    .intern-logbook-check label {
      color: var(--intern-logbook-text);
      font-size: 0.95rem;
      line-height: 1.6;
    }

    @media (max-width: 767.98px) {
      .intern-logbook-calendar-wrap,
      .intern-logbook-header {
        padding-inline: 0.95rem;
      }

      .intern-logbook-header {
        flex-direction: column;
      }

      .intern-logbook-calendar-toolbar {
        gap: 0.6rem;
      }

      .intern-logbook-month-label {
        min-width: 0;
        flex: 1 1 auto;
        font-size: 0.98rem;
      }

      .intern-logbook-month-button {
        width: 2.3rem;
        height: 2.3rem;
        border-radius: 0.75rem;
        flex: 0 0 auto;
      }

      .intern-logbook-calendar-grid {
        width: 100%;
        min-width: 0;
      }

      .intern-logbook-calendar-board {
        overflow-x: visible;
      }

      .intern-logbook-day {
        min-height: 4.85rem;
        padding: 0.15rem;
      }

      .intern-logbook-day-button {
        align-items: center;
        min-height: 4.45rem;
        gap: 0.3rem;
        padding: 0.35rem 0.15rem;
        border-radius: 0.65rem;
        text-align: center;
      }

      .intern-logbook-day-number {
        width: 1.75rem;
        height: 1.75rem;
        font-size: 0.82rem;
      }

      .intern-logbook-weekday {
        font-size: 0.72rem;
        padding: 0.35rem 0.05rem;
      }

      .intern-logbook-mobile-hint {
        display: none;
      }

      .intern-logbook-modal .modal-dialog {
        margin: 0.8rem;
      }

      .intern-logbook-modal .modal-header,
      .intern-logbook-modal .modal-body,
      .intern-logbook-modal .modal-footer {
        padding-inline: 1rem !important;
      }

      .intern-logbook-modal .modal-footer {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.65rem;
        align-items: stretch;
      }

      .intern-logbook-modal .modal-footer .btn {
        width: 100%;
        margin: 0;
      }
    }
  </style>
@endsection

@section('content')
  <div class="intern-logbook-page">
    @if (session('status'))
      <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="intern-logbook-card">
      <div class="intern-logbook-header">
        <div>
          <h1 class="intern-logbook-title">Kalender Laporan Harian</h1>
          <p class="intern-logbook-subtitle">
            Klik tanggal untuk mengisi logbook harian. Tanggal yang sudah ada titik hijau berarti logbook sudah tersimpan dan bisa diperbarui.
          </p>
        </div>
        <div class="intern-logbook-total">
          <i class="ri ri-draft-line"></i>
          {{ $totalLogbooks }} laporan bulan ini
        </div>
      </div>

      <div class="intern-logbook-calendar-wrap">
        <div class="intern-logbook-calendar-toolbar">
          @if ($canGoPreviousMonth)
            <a href="{{ route('intern.logbooks.index', ['month' => $previousMonthParam]) }}" class="intern-logbook-month-button" aria-label="Bulan sebelumnya">
              <i class="ri ri-arrow-left-s-line"></i>
            </a>
          @else
            <span class="intern-logbook-month-button" aria-label="Bulan sebelumnya" aria-disabled="true" title="Bulan sebelum masa magang dimulai tidak bisa diisi">
              <i class="ri ri-arrow-left-s-line"></i>
            </span>
          @endif
          <div class="intern-logbook-month-label">{{ $calendarMonth->translatedFormat('F Y') }}</div>
          @if ($canGoNextMonth)
            <a href="{{ route('intern.logbooks.index', ['month' => $nextMonthParam]) }}" class="intern-logbook-month-button" aria-label="Bulan berikutnya">
              <i class="ri ri-arrow-right-s-line"></i>
            </a>
          @else
            <span class="intern-logbook-month-button" aria-label="Bulan berikutnya" aria-disabled="true" title="Logbook hanya bisa diisi sampai hari ini">
              <i class="ri ri-arrow-right-s-line"></i>
            </span>
          @endif
        </div>

        <p class="intern-logbook-mobile-hint">
          Geser kalender ke samping saat membuka lewat HP untuk melihat semua hari dalam satu minggu.
        </p>
        <p class="intern-logbook-period-note">
          Tanggal sebelum masa magang dimulai dan tanggal setelah hari ini tidak bisa dipilih.
        </p>

        <div class="intern-logbook-calendar-board">
          <div class="intern-logbook-calendar-grid">
            <div class="intern-logbook-weekdays">
              @foreach ($weekdayLabels as $weekday)
                <div class="intern-logbook-weekday">{{ $weekday }}</div>
              @endforeach
            </div>

            @foreach ($calendarWeeks as $week)
              <div class="intern-logbook-week">
                @foreach ($week as $date)
                  @php
                    $dateString = $date->format('Y-m-d');
                    $isCurrentMonth = $date->month === $calendarMonth->month;
                    $isToday = $dateString === $todayString;
                    $isBeforeStart = $startDateString && $dateString < $startDateString;
                    $isAfterEnd = $endDateString && $dateString > $endDateString;
                    $isFuture = $date->isAfter(now()->startOfDay());
                    $isOutsideAllowedRange = $isBeforeStart || $isAfterEnd || $isFuture;
                    $existingLogbook = $logbooksByDate->get($dateString);
                  @endphp
                  <div class="intern-logbook-day {{ $isCurrentMonth ? '' : 'is-outside' }} {{ $isToday ? 'is-today' : '' }} {{ $existingLogbook ? 'is-filled' : '' }} {{ $isFuture ? 'is-future' : '' }} {{ $isOutsideAllowedRange ? 'is-disabled-range' : '' }}">
                    <button
                      type="button"
                      class="intern-logbook-day-button"
                      data-logbook-date="{{ $dateString }}"
                      data-logbook-id="{{ $existingLogbook?->id }}"
                      {{ $isOutsideAllowedRange ? 'disabled' : '' }}>
                      <span class="intern-logbook-day-number">{{ $date->day }}</span>
                      <span class="intern-logbook-day-dot"></span>
                    </button>
                  </div>
                @endforeach
              </div>
            @endforeach
          </div>
        </div>

        <div class="intern-logbook-legend">
          <span><i class="ri ri-checkbox-blank-circle-fill text-success"></i> Sudah ada logbook</span>
          <span><i class="ri ri-checkbox-blank-circle-line text-body-secondary"></i> Belum diisi</span>
          <span><i class="ri ri-calendar-check-line text-primary"></i> Hari ini</span>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade intern-logbook-modal" id="logbookCalendarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <form id="calendarLogbookForm" action="{{ route('intern.logbooks.store') }}" method="POST">
          @csrf
          <input type="hidden" name="_method" id="calendarLogbookMethod" value="POST">
          <input type="hidden" name="logbook_id" id="calendarLogbookId" value="{{ old('logbook_id') }}">
          <input type="hidden" name="return_month" value="{{ old('return_month', $currentMonthParam) }}">

          <div class="modal-header px-4 pt-4">
            <div>
              <h5 class="modal-title mb-1" id="calendarLogbookTitle">Isi Logbook Harian</h5>
              <small class="text-body-secondary" id="calendarLogbookDateLabel">Pilih tanggal laporan Anda.</small>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body px-4 pb-2">
            <div class="mb-4">
              <label for="calendar_tanggal" class="form-label intern-logbook-form-label">Tanggal</label>
              <input type="date" id="calendar_tanggal" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal') }}" min="{{ $startDateString }}" max="{{ now()->toDateString() }}" required>
              @error('tanggal')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label for="calendar_uraian_aktivitas" class="form-label intern-logbook-form-label">Uraian aktivitas</label>
              <textarea
                id="calendar_uraian_aktivitas"
                name="uraian_aktivitas"
                rows="4"
                class="form-control @error('uraian_aktivitas') is-invalid @enderror"
                placeholder="Tulis uraian aktivitas minimal 100 karakter"
                required>{{ old('uraian_aktivitas') }}</textarea>
              @error('uraian_aktivitas')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="intern-logbook-form-note">Minimal 100 karakter</div>
            </div>

            <div class="mb-4">
              <label for="calendar_pembelajaran_diperoleh" class="form-label intern-logbook-form-label">Pembelajaran yang diperoleh</label>
              <textarea
                id="calendar_pembelajaran_diperoleh"
                name="pembelajaran_diperoleh"
                rows="4"
                class="form-control @error('pembelajaran_diperoleh') is-invalid @enderror"
                placeholder="Tulis ilmu/pembelajaran yang diperoleh minimal 100 karakter"
                required>{{ old('pembelajaran_diperoleh') }}</textarea>
              @error('pembelajaran_diperoleh')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="intern-logbook-form-note">Minimal 100 karakter</div>
            </div>

            <div class="mb-4">
              <label for="calendar_kendala_dialami" class="form-label intern-logbook-form-label">Kendala yang dialami</label>
              <textarea
                id="calendar_kendala_dialami"
                name="kendala_dialami"
                rows="4"
                class="form-control @error('kendala_dialami') is-invalid @enderror"
                placeholder="Tulis kendala/hambatan yang dialami minimal 100 karakter">{{ old('kendala_dialami') }}</textarea>
              @error('kendala_dialami')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="intern-logbook-form-note">Minimal 100 karakter</div>
            </div>

            <div class="intern-logbook-check">
              <input type="checkbox" class="form-check-input" id="calendar_confirmation" required>
              <label for="calendar_confirmation">
                Saya menyatakan telah meninjau dan memastikan isian laporan ini sudah benar.
              </label>
            </div>
          </div>

          <div class="modal-footer px-4 pb-4">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="calendarLogbookSubmit">Simpan dan Kirim</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('page-script')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const modalEl = document.getElementById('logbookCalendarModal');
      const modal = modalEl ? new bootstrap.Modal(modalEl) : null;
      const form = document.getElementById('calendarLogbookForm');
      const methodInput = document.getElementById('calendarLogbookMethod');
      const logbookIdInput = document.getElementById('calendarLogbookId');
      const dateInput = document.getElementById('calendar_tanggal');
      const titleEl = document.getElementById('calendarLogbookTitle');
      const dateLabelEl = document.getElementById('calendarLogbookDateLabel');
      const submitEl = document.getElementById('calendarLogbookSubmit');
      const confirmationEl = document.getElementById('calendar_confirmation');
      const activityInput = form.querySelector('[name="uraian_aktivitas"]');
      const learningInput = form.querySelector('[name="pembelajaran_diperoleh"]');
      const obstacleInput = form.querySelector('[name="kendala_dialami"]');
      const updateRouteTemplate = @json(route('intern.logbooks.update', ['logbook' => '__LOGBOOK_ID__']));
      const createRoute = @json(route('intern.logbooks.store'));
      const showRouteTemplate = @json(route('intern.logbooks.show', ['logbook' => '__LOGBOOK_ID__']));
      const logbooks = @json($calendarLogbooks);
      const oldFormState = @json($oldCalendarFormState);

      function formatDate(dateString) {
        const date = new Date(dateString + 'T00:00:00');
        return new Intl.DateTimeFormat('id-ID', {
          weekday: 'long',
          day: '2-digit',
          month: 'long',
          year: 'numeric',
        }).format(date);
      }

      function applyMode(logbookId) {
        if (logbookId) {
          methodInput.value = 'PUT';
          logbookIdInput.value = logbookId;
          form.action = updateRouteTemplate.replace('__LOGBOOK_ID__', logbookId);
          titleEl.textContent = 'Perbarui Logbook Harian';
          submitEl.textContent = 'Simpan Perubahan';
        } else {
          methodInput.value = 'POST';
          logbookIdInput.value = '';
          form.action = createRoute;
          titleEl.textContent = 'Isi Logbook Harian';
          submitEl.textContent = 'Simpan dan Kirim';
        }
      }

      function populateFields(state) {
        dateInput.value = state?.tanggal ?? '';
        dateLabelEl.textContent = state?.tanggal ? formatDate(state.tanggal) : 'Pilih tanggal laporan Anda.';
        activityInput.value = state?.uraian_aktivitas ?? '';
        learningInput.value = state?.pembelajaran_diperoleh ?? '';
        obstacleInput.value = state?.kendala_dialami ?? '';
        confirmationEl.checked = false;
      }

      function openLogbookForm(logbookDate, logbook) {
        applyMode(logbook?.id ?? null);
        populateFields({
          tanggal: logbookDate,
          uraian_aktivitas: logbook?.uraian_aktivitas ?? '',
          pembelajaran_diperoleh: logbook?.pembelajaran_diperoleh ?? '',
          kendala_dialami: logbook?.kendala_dialami ?? '',
        });
      }

      document.querySelectorAll('[data-logbook-date]').forEach(function (button) {
        button.addEventListener('click', function () {
          if (button.tagName === 'A') {
            return;
          }

          const logbookDate = button.dataset.logbookDate;
          const logbook = logbooks[logbookDate] ?? null;

          if (logbook && logbook.id) {
            window.location.href = showRouteTemplate.replace('__LOGBOOK_ID__', logbook.id);
            return;
          }

          openLogbookForm(logbookDate, logbook);
          modal?.show();
        });
      });

      @if ($errors->any() && old('tanggal'))
        applyMode(oldFormState.logbook_id || null);
        populateFields(oldFormState);
        modal?.show();
      @endif
    });
  </script>
@endsection
