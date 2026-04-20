@extends('layouts.app')

@section('title', 'Import Intern')
@section('page_heading', 'Import Intern')

@section('content')
    <section class="page-intro card-surface">
        <div>
            <p class="eyebrow">Admin Area</p>
            <h2>Import Data Intern</h2>
            <p>Upload data awal intern agar mereka bisa registrasi memakai email dan NIM/NIS.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.interns.template') }}" class="button">Download Template</a>
            <a href="{{ route('admin.interns.index') }}" class="button button-muted">Daftar Intern</a>
        </div>
    </section>

    <section class="hero-grid">
        <article class="side-card">
            <h3>Kolom Excel</h3>
            <ul class="progress-list">
                <li>nama</li>
                <li>email</li>
                <li>nim_nis</li>
                <li>tipe_peserta</li>
                <li>divisi</li>
                <li>tanggal_mulai_magang</li>
                <li>tanggal_selesai_magang</li>
            </ul>
        </article>

        <article class="side-card">
            <h3>Upload File</h3>
            <form action="{{ route('admin.interns.import.store') }}" method="POST" enctype="multipart/form-data" class="auth-form">
                @csrf

                <div class="form-group">
                    <label for="file">File Excel / CSV</label>
                    <input id="file" type="file" name="file" accept=".xlsx,.csv" required>
                    @error('file')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="button">Import Data</button>
            </form>
        </article>
    </section>
@endsection
