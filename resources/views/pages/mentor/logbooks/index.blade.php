@extends('layouts/contentNavbarLayout')

@section('title', 'Logbook Anak Bimbingan')

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
  $logbooksByDate = $logbooks->groupBy(fn ($logbook) => $logbook->tanggal->format('Y-m-d'));
  $previousMonthParam = $calendarMonth->copy()->subMonth()->format('Y-m');
  $nextMonthParam = $calendarMonth->copy()->addMonth()->format('Y-m');
  $todayString = now()->format('Y-m-d');
  $calendarPayload = $logbooksByDate
      ->map(fn ($items) => $items->map(fn ($logbook) => [
          'id' => $logbook->id,
          'tanggal' => $logbook->tanggal->format('Y-m-d'),
          'uraian_aktivitas' => $logbook->uraian_aktivitas,
          'intern_nama' => $logbook->intern->user->name ?? $logbook->intern->name,
          'divisi' => $logbook->intern->division->name ?? 'Tanpa Divisi',
          'detail_url' => route('mentor.logbooks.show', $logbook->id),
      ])->values())
      ->toArray();
@endphp

@section('page-style')
  <style>
    .mentor-logbook-page {
      display: grid;
      gap: 1rem;
    }

    .mentor-logbook-shell {
      border: 1px solid rgba(148, 163, 184, 0.14);
      border-radius: 1.6rem;
      background: rgba(255, 255, 255, 0.97);
      box-shadow: 0 16px 42px rgba(15, 23, 42, 0.06);
    }

    .mentor-logbook-header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 1rem;
      padding: 1.35rem 1.4rem 0;
    }

    .mentor-logbook-title {
      margin: 0;
      color: #172033;
      font-size: 1.35rem;
      font-weight: 800;
      letter-spacing: -0.03em;
    }

    .mentor-logbook-subtitle {
      margin: 0.45rem 0 0;
      color: #64748b;
      font-size: 0.92rem;
      line-height: 1.6;
    }

    .mentor-logbook-total {
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      padding: 0.7rem 0.9rem;
      border-radius: 999px;
      background: rgba(37, 99, 235, 0.08);
      color: #2563eb;
      font-size: 0.86rem;
      font-weight: 700;
      white-space: nowrap;
    }

    .mentor-logbook-calendar-wrap {
      padding: 1.1rem 1.4rem 1.4rem;
    }

    .mentor-logbook-calendar-board {
      overflow-x: auto;
      padding-bottom: 0.35rem;
      -webkit-overflow-scrolling: touch;
    }

    .mentor-logbook-calendar-grid {
      min-width: 52rem;
    }

    .mentor-logbook-toolbar {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 1rem;
      margin-bottom: 1.2rem;
    }

    .mentor-logbook-month-button {
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

    .mentor-logbook-month-button:hover {
      transform: translateY(-1px);
      border-color: rgba(37, 99, 235, 0.24);
      box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
      color: #1d4ed8;
    }

    .mentor-logbook-month-label {
      min-width: 11rem;
      text-align: center;
      color: #1f2937;
      font-size: 1.25rem;
      font-weight: 700;
      letter-spacing: -0.02em;
    }

    .mentor-logbook-weekdays,
    .mentor-logbook-week {
      display: grid;
      grid-template-columns: repeat(7, minmax(0, 1fr));
    }

    .mentor-logbook-weekdays {
      margin-bottom: 0.35rem;
      padding-inline: 0.15rem;
    }

    .mentor-logbook-weekday {
      padding: 0.55rem;
      color: #334155;
      font-size: 0.92rem;
      font-weight: 600;
      text-align: center;
    }

    .mentor-logbook-week {
      border-top: 1px solid rgba(148, 163, 184, 0.18);
    }

    .mentor-logbook-day {
      min-height: 8rem;
      padding: 0.4rem;
      background: rgba(248, 250, 252, 0.45);
      border-right: 1px solid rgba(148, 163, 184, 0.12);
    }

    .mentor-logbook-week .mentor-logbook-day:last-child {
      border-right: 0;
    }

    .mentor-logbook-day-button {
      width: 100%;
      height: 100%;
      min-height: 7.1rem;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: flex-start;
      gap: 0.55rem;
      padding: 0.75rem;
      border: 0;
      border-radius: 1rem;
      background: transparent;
      color: #64748b;
      text-align: left;
      transition: background 0.18s ease, transform 0.18s ease, color 0.18s ease;
    }

    .mentor-logbook-day-button:hover:not(:disabled) {
      background: rgba(37, 99, 235, 0.08);
      color: #1d4ed8;
      transform: translateY(-1px);
    }

    .mentor-logbook-day-button:disabled {
      cursor: default;
    }

    .mentor-logbook-day-number {
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

    .mentor-logbook-day.is-today .mentor-logbook-day-number {
      background: #2d8cff;
      color: #fff;
    }

    .mentor-logbook-day.is-has-logbook .mentor-logbook-day-button {
      color: #1f2937;
    }

    .mentor-logbook-day.is-has-logbook .mentor-logbook-day-button:hover {
      background: rgba(34, 197, 94, 0.08);
      color: #166534;
    }

    .mentor-logbook-day.is-outside {
      background: rgba(249, 250, 251, 0.72);
    }

    .mentor-logbook-day.is-outside .mentor-logbook-day-button {
      color: #c0c6d4;
    }

    .mentor-logbook-day-summary {
      display: grid;
      gap: 0.25rem;
      width: 100%;
    }

    .mentor-logbook-day-count {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      color: #2563eb;
      font-size: 0.78rem;
      font-weight: 700;
    }

    .mentor-logbook-day-names {
      color: #475569;
      font-size: 0.76rem;
      line-height: 1.45;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .mentor-logbook-day-empty {
      color: #cbd5e1;
      font-size: 0.76rem;
      font-weight: 600;
    }

    .mentor-logbook-legend {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      margin-top: 1rem;
      color: #64748b;
      font-size: 0.82rem;
    }

    .mentor-logbook-legend span {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
    }

    .mentor-logbook-mobile-hint {
      display: none;
      margin-bottom: 0.9rem;
      color: #64748b;
      font-size: 0.82rem;
      line-height: 1.5;
    }

    .mentor-logbook-legend-dot {
      width: 0.65rem;
      height: 0.65rem;
      border-radius: 50%;
      background: #22c55e;
    }

    .mentor-logbook-modal .modal-content {
      border: 0;
      border-radius: 1.35rem;
      box-shadow: 0 24px 54px rgba(15, 23, 42, 0.18);
    }

    .mentor-logbook-modal .modal-header,
    .mentor-logbook-modal .modal-footer {
      border: 0;
    }

    .mentor-logbook-modal-list {
      display: grid;
      gap: 0.8rem;
    }

    .mentor-logbook-modal-item {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 1rem;
      padding: 1rem 1.05rem;
      border: 1px solid rgba(148, 163, 184, 0.16);
      border-radius: 1rem;
      background: rgba(248, 250, 252, 0.72);
    }

    .mentor-logbook-modal-name {
      color: #1f2937;
      font-size: 0.98rem;
      font-weight: 700;
    }

    .mentor-logbook-modal-meta {
      margin-top: 0.2rem;
      color: #64748b;
      font-size: 0.82rem;
    }

    .mentor-logbook-modal-text {
      margin-top: 0.45rem;
      color: #475569;
      font-size: 0.84rem;
      line-height: 1.55;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    @media (max-width: 767.98px) {
      .mentor-logbook-header,
      .mentor-logbook-calendar-wrap {
        padding-inline: 1rem;
      }

      .mentor-logbook-header {
        flex-direction: column;
      }

      .mentor-logbook-month-label {
        min-width: 0;
        flex: 1 1 auto;
        font-size: 1.05rem;
      }

      .mentor-logbook-toolbar {
        gap: 0.65rem;
      }

      .mentor-logbook-month-button {
        width: 2.45rem;
        height: 2.45rem;
        border-radius: 0.8rem;
        flex: 0 0 auto;
      }

      .mentor-logbook-calendar-grid {
        min-width: 40rem;
      }

      .mentor-logbook-day {
        min-height: 6rem;
        padding: 0.25rem;
      }

      .mentor-logbook-day-button {
        min-height: 5.2rem;
        padding: 0.5rem;
        border-radius: 0.8rem;
      }

      .mentor-logbook-weekday {
        font-size: 0.8rem;
        padding: 0.4rem 0.15rem;
      }

      .mentor-logbook-day-count,
      .mentor-logbook-day-empty,
      .mentor-logbook-day-names {
        font-size: 0.72rem;
      }

      .mentor-logbook-mobile-hint {
        display: block;
      }

      .mentor-logbook-modal .modal-dialog {
        margin: 0.9rem;
      }

      .mentor-logbook-modal-item {
        flex-direction: column;
      }

      .mentor-logbook-modal-item .btn {
        width: 100%;
      }
    }
  </style>
@endsection

@section('content')
  <div class="mentor-logbook-page">
    @include('partials.app-breadcrumb', [
      'items' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.mentor')],
        ['label' => 'Logbook Intern', 'current' => true],
      ],
    ])

    <div class="mentor-logbook-shell">
      <div class="mentor-logbook-header">
        <div>
          <h1 class="mentor-logbook-title">Kalender Logbook Anak Bimbingan</h1>
          <p class="mentor-logbook-subtitle">
            Pantau logbook intern bimbingan per tanggal. Klik tanggal yang memiliki laporan untuk melihat siapa saja yang mengirim logbook pada hari itu.
          </p>
        </div>
        <div class="mentor-logbook-total">
          <i class="ri ri-draft-line"></i>
          {{ $logbooks->count() }} laporan bulan ini
        </div>
      </div>

      <div class="mentor-logbook-calendar-wrap">
        <div class="mentor-logbook-toolbar">
          <a href="{{ route('mentor.logbooks.index', ['month' => $previousMonthParam]) }}" class="mentor-logbook-month-button" aria-label="Bulan sebelumnya">
            <i class="ri ri-arrow-left-s-line"></i>
          </a>
          <div class="mentor-logbook-month-label">{{ $calendarMonth->translatedFormat('F Y') }}</div>
          <a href="{{ route('mentor.logbooks.index', ['month' => $nextMonthParam]) }}" class="mentor-logbook-month-button" aria-label="Bulan berikutnya">
            <i class="ri ri-arrow-right-s-line"></i>
          </a>
        </div>

        <p class="mentor-logbook-mobile-hint">
          Geser kalender ke samping saat membuka lewat HP agar semua hari dalam satu minggu tetap mudah dipantau.
        </p>

        <div class="mentor-logbook-calendar-board">
          <div class="mentor-logbook-calendar-grid">
            <div class="mentor-logbook-weekdays">
              @foreach ($weekdayLabels as $weekday)
                <div class="mentor-logbook-weekday">{{ $weekday }}</div>
              @endforeach
            </div>

            @foreach ($calendarWeeks as $week)
              <div class="mentor-logbook-week">
                @foreach ($week as $date)
                  @php
                    $dateString = $date->format('Y-m-d');
                    $isCurrentMonth = $date->month === $calendarMonth->month;
                    $isToday = $dateString === $todayString;
                    $dayLogbooks = $logbooksByDate->get($dateString, collect());
                    $internNames = $dayLogbooks
                        ->map(fn ($logbook) => $logbook->intern->user->name ?? $logbook->intern->name)
                        ->unique()
                        ->take(2)
                        ->implode(', ');
                  @endphp
                  <div class="mentor-logbook-day {{ $isCurrentMonth ? '' : 'is-outside' }} {{ $isToday ? 'is-today' : '' }} {{ $dayLogbooks->isNotEmpty() ? 'is-has-logbook' : '' }}">
                    <button
                      type="button"
                      class="mentor-logbook-day-button"
                      data-logbook-date="{{ $dateString }}"
                      {{ $dayLogbooks->isEmpty() ? 'disabled' : '' }}>
                      <span class="mentor-logbook-day-number">{{ $date->day }}</span>
                      <span class="mentor-logbook-day-summary">
                        @if ($dayLogbooks->isNotEmpty())
                          <span class="mentor-logbook-day-count">
                            <i class="ri ri-checkbox-blank-circle-fill"></i>
                            {{ $dayLogbooks->count() }} laporan
                          </span>
                          <span class="mentor-logbook-day-names">{{ $internNames }}</span>
                        @else
                          <span class="mentor-logbook-day-empty">Belum ada laporan</span>
                        @endif
                      </span>
                    </button>
                  </div>
                @endforeach
              </div>
            @endforeach
          </div>
        </div>

        <div class="mentor-logbook-legend">
          <span><i class="mentor-logbook-legend-dot"></i> Tanggal memiliki logbook</span>
          <span><i class="ri ri-calendar-check-line text-primary"></i> Hari ini</span>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade mentor-logbook-modal" id="mentorLogbookCalendarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header px-4 pt-4">
          <div>
            <h5 class="modal-title mb-1" id="mentorLogbookModalTitle">Logbook Tanggal</h5>
            <small class="text-body-secondary" id="mentorLogbookModalSubtitle">Daftar laporan intern pada tanggal ini.</small>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body px-4 pb-4">
          <div class="mentor-logbook-modal-list" id="mentorLogbookModalList"></div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('page-script')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const modalEl = document.getElementById('mentorLogbookCalendarModal');
      const modal = modalEl ? new bootstrap.Modal(modalEl) : null;
      const titleEl = document.getElementById('mentorLogbookModalTitle');
      const subtitleEl = document.getElementById('mentorLogbookModalSubtitle');
      const listEl = document.getElementById('mentorLogbookModalList');
      const calendarLogbooks = @json($calendarPayload);

      function formatDate(dateString) {
        const date = new Date(dateString + 'T00:00:00');
        return new Intl.DateTimeFormat('id-ID', {
          weekday: 'long',
          day: '2-digit',
          month: 'long',
          year: 'numeric',
        }).format(date);
      }

      function truncate(text, maxLength) {
        if (!text || text.length <= maxLength) {
          return text || '';
        }

        return text.slice(0, maxLength).trim() + '...';
      }

      function renderItems(dateString) {
        const entries = calendarLogbooks[dateString] || [];

        titleEl.textContent = 'Logbook ' + formatDate(dateString);
        subtitleEl.textContent = entries.length + ' laporan intern pada tanggal ini.';
        listEl.innerHTML = '';

        entries.forEach(function (entry) {
          const item = document.createElement('div');
          item.className = 'mentor-logbook-modal-item';
          item.innerHTML = `
            <div>
              <div class="mentor-logbook-modal-name">${entry.intern_nama}</div>
              <div class="mentor-logbook-modal-meta">${entry.divisi}</div>
              <div class="mentor-logbook-modal-text">${truncate(entry.uraian_aktivitas, 140)}</div>
            </div>
            <a href="${entry.detail_url}" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
          `;
          listEl.appendChild(item);
        });
      }

      document.querySelectorAll('[data-logbook-date]').forEach(function (button) {
        button.addEventListener('click', function () {
          const dateString = button.dataset.logbookDate;
          const entries = calendarLogbooks[dateString] || [];

          if (!entries.length) {
            return;
          }

          if (entries.length === 1) {
            window.location.href = entries[0].detail_url;
            return;
          }

          renderItems(dateString);
          modal?.show();
        });
      });
    });
  </script>
@endsection
