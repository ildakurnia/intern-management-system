@extends('layouts.app')

@section('title', 'Logbook Anak Bimbingan')

@section('content')
    <div class="settings-page-head">
        <div>
            <h1>Logbook Anak Bimbingan</h1>
            <div class="breadcrumb-line">
                <span>Mentor</span>
                <span>&rsaquo;</span>
                <span>Logbook</span>
            </div>
        </div>
    </div>

    <div class="settings-form-card">
        <div class="settings-access-list">
            <div class="settings-access-label">Laporan Harian Anak Bimbingan Anda</div>

            @forelse ($logbooks as $logbook)
                <div class="settings-access-group">
                    <div class="settings-access-row" style="padding: 1.25rem;">
                        <span class="settings-access-child-name">
                            <span class="settings-access-title-block">
                                <strong style="color: var(--primary);">{{ $logbook->intern->user->name }}</strong>
                                <span style="display: block; font-size: 0.9rem; font-weight: 500; margin-top: 0.2rem;">
                                    {{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('d F Y') }}
                                </span>
                                <small style="display: block; color: var(--text-muted); margin-top: 0.25rem;">
                                    {{ Str::limit($logbook->uraian_aktivitas, 100) }}
                                </small>
                            </span>
                        </span>

                        <span class="settings-icon-actions">
                            <a href="{{ route('mentor.logbooks.show', $logbook->id) }}" class="icon-action" title="Buka Detail">
                                <svg viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path fill="currentColor" d="M12 9a3 3 0 0 0-3 3 3 3 0 0 0 3 3 3 3 0 0 0 3-3 3 3 0 0 0-3-3m0 8a5 5 0 0 1-5-5 5 5 0 0 1 5-5 5 5 0 0 1 5 5 5 5 0 0 1-5 5m0-12.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5Z"/></svg>
                            </a>
                        </span>
                    </div>
                </div>
            @empty
                <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
                    <p>Belum ada laporan masuk dari anak bimbingan Anda.</p>
                </div>
            @endforelse

            @if($logbooks->hasPages())
                <div style="padding: 1.5rem; border-top: 1px solid var(--border-soft); display: flex; justify-content: center;">
                    {{ $logbooks->links('pagination::simple-bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
@endsection
