@extends('layouts.app')

@section('title', 'Detail Logbook')

@section('content')
    <section class="page-intro card-surface">
        <div>
            <p class="eyebrow">Aktivitas Magang</p>
            <h2>Detail Logbook</h2>
            <p>Logbook tanggal {{ $logbook->tanggal->format('d M Y') }}.</p>
        </div>
        <div class="hero-actions">
            @can('intern.logbooks.edit')
                <a href="{{ route('intern.logbooks.edit', $logbook) }}" class="button">Edit</a>
            @endcan
            @can('intern.logbooks.destroy')
                <form action="{{ route('intern.logbooks.destroy', $logbook) }}" method="POST" onsubmit="return confirm('Hapus logbook ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="button button-muted danger-action">Hapus</button>
                </form>
            @endcan
            <a href="{{ route('intern.logbooks.index') }}" class="button button-muted">Kembali</a>
        </div>
    </section>

    <section class="detail-grid detail-grid-wide">
        <article class="card-surface detail-card">
            <h3>Uraian Aktivitas</h3>
            <p>{{ $logbook->uraian_aktivitas }}</p>
        </article>

        <article class="card-surface detail-card">
            <h3>Informasi</h3>
            <dl class="detail-list">
                <div>
                    <dt>Tanggal</dt>
                    <dd>{{ $logbook->tanggal->format('d M Y') }}</dd>
                </div>
                <div>
                    <dt>Dibuat</dt>
                    <dd>{{ $logbook->created_at->format('d M Y H:i') }}</dd>
                </div>
            </dl>
        </article>

        <article class="card-surface detail-card">
            <h3>Pembelajaran yang Diperoleh</h3>
            <p>{{ $logbook->pembelajaran_diperoleh }}</p>
        </article>

        <article class="card-surface detail-card">
            <h3>Kendala yang Dialami</h3>
            <p>{{ $logbook->kendala_dialami ?: 'Tidak ada kendala yang dicatat.' }}</p>
        </article>
    </section>
@endsection
