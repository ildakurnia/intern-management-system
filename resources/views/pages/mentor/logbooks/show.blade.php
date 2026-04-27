@extends('layouts.app')

@section('title', 'Detail Laporan Anak Bimbingan')

@section('content')
    <div class="settings-page-head">
        <div>
            <h1>Detail Logbook</h1>
            <div class="breadcrumb-line">
                <span>Mentor</span>
                <span>&rsaquo;</span>
                <a href="{{ route('mentor.logbooks.index') }}">Data Logbook</a>
                <span>&rsaquo;</span>
                <span>Detail</span>
            </div>
        </div>
    </div>

    <div class="settings-form-card" style="max-width: 850px; padding: 2.5rem;">
        <div style="margin-bottom: 2rem; border-bottom: 2px solid var(--border-soft); padding-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <h4 style="color: var(--text-muted); margin: 0 0 0.25rem 0; font-size: 0.8rem; text-transform: uppercase;">Laporan Oleh Intern:</h4>
                <h2 style="margin: 0; color: var(--text-main);">{{ $logbook->intern->user->name }}</h2>
                <span style="color: var(--primary); font-weight: 500;">Status: Aktif</span>
            </div>
            <div style="text-align: right;">
                <span style="display: block; font-weight: 600; font-size: 1.1rem; color: var(--primary);">{{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>

        <div style="margin-bottom: 2rem;">
            <h4 style="color: var(--text-muted); margin-bottom: 0.75rem; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;">Pekerjaan yang Dilakukan:</h4>
            <div style="line-height: 1.7; font-size: 1.05rem; color: #2d3748; background: #fdfdfd; border: 1px solid var(--border-soft); padding: 1.5rem; border-radius: 0.5rem;">
                {{ $logbook->uraian_aktivitas }}
            </div>
        </div>

        <div style="margin-bottom: 2rem;">
            <h4 style="color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;">Hasil Pembelajaran:</h4>
            <p style="line-height: 1.6; font-style: italic; color: #4a5568;">"{{ $logbook->pembelajaran_diperoleh }}"</p>
        </div>

        @if($logbook->kendala_dialami)
        <div style="padding: 1.25rem; background: #fff5f5; border: 1px solid #fed7d7; border-radius: 0.4rem;">
            <h4 style="color: #c53030; margin-bottom: 0.4rem; font-size: 0.85rem; font-weight: 700;">Kendala Magang:</h4>
            <p style="color: #742a2a; margin: 0;">{{ $logbook->kendala_dialami }}</p>
        </div>
        @endif

        <div style="margin-top: 3rem; pt-2; border-top: 1px solid var(--border-soft);">
            <a href="{{ route('mentor.logbooks.index') }}" class="button" style="background: var(--bg-soft); color: var(--text-main); text-decoration: none;">&larr; Kembali ke Daftar</a>
        </div>
    </div>
@endsection
