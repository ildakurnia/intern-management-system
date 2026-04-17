@extends('layouts.app')

@section('title', $title ?? 'Fitur')

@section('content')
    <div class="page-intro card-surface">
        <div>
            <p class="eyebrow">Akses Modul Diizinkan</p>
            <h2>{{ $title ?? 'Modul Aktif' }}</h2>
            <p>Anda berhasil mengakses halaman ini karena role Anda memiliki **Permission** yang tepat di dalam sistem RBAC Spatie.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="button button-muted">Kembali ke Dashboard</a>
    </div>

    <div class="card-surface mt-4" style="margin-top: 1.5rem; padding: 2rem; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 300px; text-align: center;">
        <div class="stat-icon" style="margin: 0 auto 1.5rem;"></div>
        <h3 style="margin-bottom: 0.5rem;">Modul Under Construction</h3>
        <p style="color: var(--text-muted); max-width: 400px; line-height: 1.6;">
            Halaman fungsi untuk <strong>{{ $title ?? 'fitur ini' }}</strong> sedang disiapkan. Yang penting, sistem gerbang jalur Permission-nya sudah 100% bekerja sempurna!
        </p>
    </div>
@endsection
