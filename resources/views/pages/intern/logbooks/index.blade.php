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
@endphp

@section('page-style')
  <style>
    .intern-logbook-page {
      display: grid;
      gap: 1rem;
    }

    .intern-logbook-card {
      border: 1px solid rgba(148, 163, 184, 0.14);
      border-radius: 1.6rem;
      background: rgba(255, 255, 255, 0.97);
      box-shadow: 0 16px 42px rgba(15, 23, 42, 0.06);
    }

    .intern-logbook-header {
      padding: 1.35rem 1.4rem 0;
    }

    .intern-logbook-title {
      margin: 0;
      color: #172033;
      font-size: 1.35rem;
      font-weight: 800;
      letter-spacing: -0.03em;
    }

    .intern-logbook-subtitle {
      margin: 0.45rem 0 0;
      color: #64748b;
      font-size: 0.92rem;
      line-height: 1.6;
    }

    .intern-logbook-calendar-wrap {
      padding: 1.1rem 1.4rem 1.4rem;
    }

    .intern-logbook-calendar-board {
      overflow-x: auto;
      padding-bottom: 0.35rem;
      -webkit-overflow-scrolling: touch;
    }

    .intern-logbook-calendar-grid {
      min-width: 42rem;
    }

    .intern-logbook-calendar-toolbar {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 1rem;
      margin-bottom: 1.2rem;
    }

    .intern-logbook-month-button {
      width: 2.8rem;
      height: 2.8rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border: 1px solid rgba(15, 23, 42, 0.22);
      border-radius: 0.9rem;
      color: #1f2937;
      background: #fff;
      text-decoration: none;
      transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .intern-logbook-month-button:hover {
      transform: translateY(-1px);
      border-color: rgba(37, 99, 235, 0.24);
      box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
      color: #1d4ed8;
    }

    .intern-logbook-month-label {
      min-width: 10rem;
      text-align: center;
      color: #1f2937;
      font-size: 1.25rem;
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
      padding: 0.55rem;
      color: #334155;
      font-size: 0.92rem;
      font-weight: 600;
      text-align: center;
    }

    .intern-logbook-week {
      border-top: 1px solid rgba(148, 163, 184, 0.18);
    }

    .intern-logbook-day {
      min-height: 6.2rem;
      padding: 0.4rem;
      background: rgba(248, 250, 252, 0.45);
      border-right: 1px solid rgba(148, 163, 184, 0.12);
    }

    .intern-logbook-week .intern-logbook-day:last-child {
      border-right: 0;
    }

    .intern-logbook-day-button {
      width: 100%;
      height: 100%;
      min-height: 5.3rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 0.45rem;
      border: 0;
      border-radius: 1rem;
      background: transparent;
      color: #64748b;
      transition: background 0.18s ease, transform 0.18s ease, color 0.18s ease;
    }

    .intern-logbook-day-button:hover:not(:disabled) {
      background: rgba(37, 99, 235, 0.08);
      color: #1d4ed8;
      transform: translateY(-1px);
    }

    .intern-logbook-day-button:disabled {
      cursor: not-allowed;
      opacity: 0.72;
    }

    .intern-logbook-day-number {
      width: 2.2rem;
      height: 2.2rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      font-size: 1rem;
      font-weight: 600;
      line-height: 1;
    }

    .intern-logbook-day.is-today .intern-logbook-day-number {
      background: #2d8cff;
      color: #fff;
    }

    .intern-logbook-day.is-filled .intern-logbook-day-button {
      color: #166534;
    }

    .intern-logbook-day.is-filled .intern-logbook-day-button:hover {
      background: rgba(34, 197, 94, 0.1);
      color: #166534;
    }

    .intern-logbook-day-dot {
      width: 0.55rem;
      height: 0.55rem;
      border-radius: 50%;
      border: 2px solid rgba(148, 163, 184, 0.48);
      background: transparent;
    }

    .intern-logbook-day.is-filled .intern-logbook-day-dot {
      border-color: #3f9142;
      background: #3f9142;
    }

    .intern-logbook-day.is-today .intern-logbook-day-dot {
      border-color: #2d8cff;
      background: #2d8cff;
    }

    .intern-logbook-day.is-outside {
      background: rgba(249, 250, 251, 0.72);
    }

    .intern-logbook-day.is-outside .intern-logbook-day-button {
      color: #c0c6d4;
    }

    .intern-logbook-day.is-future .intern-logbook-day-button {
      color: #b7becd;
    }

    .intern-logbook-legend {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      margin-top: 1rem;
      color: #64748b;
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
      color: #64748b;
      font-size: 0.82rem;
      line-height: 1.5;
    }

    .intern-logbook-modal .modal-content {
      border: 0;
      border-radius: 1.35rem;
      box-shadow: 0 24px 54px rgba(15, 23, 42, 0.18);
    }

    .intern-logbook-modal .modal-header,
    .intern-logbook-modal .modal-footer {
      border: 0;
    }

    .intern-logbook-modal .modal-body {
      padding-top: 0;
    }

    .intern-logbook-form-label {
      color: #1f2937;
      font-size: 0.95rem;
      font-weight: 600;
    }

    .intern-logbook-form-note {
      margin-top: 0.35rem;
      color: #ef4444;
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
      color: #374151;
      font-size: 0.95rem;
      line-height: 1.6;
    }

    @media (max-width: 767.98px) {
      .intern-logbook-calendar-wrap,
      .intern-logbook-header {
        padding-inline: 1rem;
      }

      .intern-logbook-calendar-toolbar {
        gap: 0.65rem;
      }

      .intern-logbook-month-label {
        min-width: 0;
        flex: 1 1 auto;
        font-size: 1.05rem;
      }

      .intern-logbook-month-button {
        width: 2.45rem;
        height: 2.45rem;
        border-radius: 0.8rem;
        flex: 0 0 auto;
      }

      .intern-logbook-calendar-grid {
        min-width: 34rem;
      }

      .intern-logbook-day {
        min-height: 5rem;
        padding: 0.25rem;
      }

      .intern-logbook-day-button {
        min-height: 4.3rem;
        border-radius: 0.8rem;
      }

      .intern-logbook-weekday {
        font-size: 0.8rem;
        padding: 0.4rem 0.15rem;
      }

      .intern-logbook-mobile-hint {
        display: block;
      }

      .intern-logbook-modal .modal-dialog {
        margin: 0.9rem;
      }

      .intern-logbook-modal .modal-header,
      .intern-logbook-modal .modal-body,
      .intern-logbook-modal .modal-footer {
        padding-inline: 1rem !important;
      }

      .intern-logbook-modal .modal-footer {
        flex-direction: column-reverse;
        align-items: stretch;
      }

      .intern-logbook-modal .modal-footer .btn {
        width: 100%;
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
        <h1 class="intern-logbook-title">Kalender Laporan Harian</h1>
        <p class="intern-logbook-subtitle">
          Klik tanggal untuk mengisi logbook harian. Tanggal yang sudah ada titik hijau berarti logbook sudah tersimpan dan bisa diperbarui.
        </p>
      </div>

      <div class="intern-logbook-calendar-wrap">
        <div class="intern-logbook-calendar-toolbar">
          <a href="{{ route('intern.logbooks.index', ['month' => $previousMonthParam]) }}" class="intern-logbook-month-button" aria-label="Bulan sebelumnya">
            <i class="ri ri-arrow-left-s-line"></i>
          </a>
          <div class="intern-logbook-month-label">{{ $calendarMonth->translatedFormat('F Y') }}</div>
          <a href="{{ route('intern.logbooks.index', ['month' => $nextMonthParam]) }}" class="intern-logbook-month-button" aria-label="Bulan berikutnya">
            <i class="ri ri-arrow-right-s-line"></i>
          </a>
        </div>

        <p class="intern-logbook-mobile-hint">
          Geser kalender ke samping saat membuka lewat HP untuk melihat semua hari dalam satu minggu.
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
                    $isFuture = $date->isAfter(now()->startOfDay());
                    $existingLogbook = $logbooksByDate->get($dateString);
                  @endphp
                  <div class="intern-logbook-day {{ $isCurrentMonth ? '' : 'is-outside' }} {{ $isToday ? 'is-today' : '' }} {{ $existingLogbook ? 'is-filled' : '' }} {{ $isFuture ? 'is-future' : '' }}">
                    <button
                      type="button"
                      class="intern-logbook-day-button"
                      data-logbook-date="{{ $dateString }}"
                      data-logbook-id="{{ $existingLogbook?->id }}"
                      {{ $isFuture ? 'disabled' : '' }}>
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
              <input type="date" id="calendar_tanggal" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal') }}" max="{{ now()->toDateString() }}" required>
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
