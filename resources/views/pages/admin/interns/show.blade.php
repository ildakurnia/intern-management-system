@extends('layouts.app')

@section('title', 'Detail Intern')
@section('page_heading', 'Detail Intern')

@php
    $identifierLabel = $intern->type === 'mahasiswa' ? 'NIM' : 'NIS';
    $identifierValue = $intern->type === 'mahasiswa' ? $intern->nim : $intern->nis;
@endphp

@section('content')
    <section class="page-intro card-surface">
        <div>
            <p class="eyebrow">Admin Area</p>
            <h2>{{ $intern->name }}</h2>
            <p>Detail biodata, status onboarding, dan kelengkapan berkas intern.</p>
        </div>
        <a href="{{ route('admin.interns.index') }}" class="button button-muted">Kembali</a>
    </section>

    <section class="detail-grid">
        <article class="card-surface detail-card">
            <h3>Data Dasar</h3>
            <dl class="detail-list">
                <div>
                    <dt>Nama</dt>
                    <dd>{{ $intern->name }}</dd>
                </div>
                <div>
                    <dt>Email</dt>
                    <dd>{{ $intern->email ?? '-' }}</dd>
                </div>
                <div>
                    <dt>{{ $identifierLabel }}</dt>
                    <dd>{{ $identifierValue ?? '-' }}</dd>
                </div>
                <div>
                    <dt>Tipe Peserta</dt>
                    <dd>{{ ucfirst($intern->type) }}</dd>
                </div>
                <div>
                    <dt>Divisi</dt>
                    <dd>{{ $intern->division?->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt>Mentor Pembimbing</dt>
                    <dd>{{ $intern->mentor?->name ?? 'Belum ditentukan' }}</dd>
                </div>
                <div>
                    <dt>Periode Magang</dt>
                    <dd>{{ optional($intern->start_date)->format('d M Y') }} -
                        {{ optional($intern->end_date)->format('d M Y') }}</dd>
                </div>
                <div>
                    <dt>Status Magang</dt>
                    <dd>{{ ucfirst($intern->status) }}</dd>
                </div>
                <div>
                    <dt>Akun Login</dt>
                    <dd>{{ $intern->user ? 'Terhubung' : 'Belum registrasi' }}</dd>
                </div>
            </dl>
        </article>

        <article class="card-surface detail-card">
            <h3>Status Onboarding</h3>
            <div class="status-stack">
                <div class="status-item">
                    <span>Registrasi</span>
                    <strong
                        class="pill {{ $intern->registration_status === 'registered' ? 'pill-success' : '' }}">{{ $intern->registration_status }}</strong>
                </div>
                <div class="status-item">
                    <span>Profil</span>
                    <strong
                        class="pill {{ $intern->hasCompletedProfile() ? 'pill-success' : '' }}">{{ $intern->hasCompletedProfile() ? 'Lengkap' : 'Belum' }}</strong>
                </div>
                <div class="status-item">
                    <span>Berkas</span>
                    <strong
                        class="pill {{ $intern->hasCompletedDocuments() ? 'pill-success' : '' }}">{{ $intern->hasCompletedDocuments() ? 'Lengkap' : 'Belum' }}</strong>
                </div>
            </div>
        </article>
    </section>

    <section class="detail-grid detail-grid-wide">
        <article class="card-surface detail-card">
            <h3>Biodata</h3>
            <dl class="detail-list">
                <div>
                    <dt>No HP</dt>
                    <dd>{{ $intern->phone ?? '-' }}</dd>
                </div>
                <div>
                    <dt>Tanggal Lahir</dt>
                    <dd>{{ optional($intern->birth_date)->format('d M Y') ?? '-' }}</dd>
                </div>
                <div>
                    <dt>Jenis Kelamin</dt>
                    <dd>{{ $intern->gender === 'male' ? 'Laki-laki' : ($intern->gender === 'female' ? 'Perempuan' : '-') }}
                    </dd>
                </div>
                <div>
                    <dt>Asal Sekolah/Kampus</dt>
                    <dd>{{ $intern->institution ?? '-' }}</dd>
                </div>
                <div>
                    <dt>Jurusan</dt>
                    <dd>{{ $intern->major ?? '-' }}</dd>
                </div>
                <div>
                    <dt>Fakultas</dt>
                    <dd>{{ $intern->faculty ?? '-' }}</dd>
                </div>
                <div>
                    <dt>Kelas</dt>
                    <dd>{{ $intern->school_grade ?? '-' }}</dd>
                </div>
                <div>
                    <dt>Semester</dt>
                    <dd>{{ $intern->semester ?? '-' }}</dd>
                </div>
                <div>
                    <dt>IPK</dt>
                    <dd>{{ $intern->gpa ?? '-' }}</dd>
                </div>
                <div class="detail-span">
                    <dt>Alamat</dt>
                    <dd>{{ $intern->address ?? '-' }}</dd>
                </div>
                <div class="detail-span">
                    <dt>Catatan</dt>
                    <dd>{{ $intern->notes ?? '-' }}</dd>
                </div>
            </dl>
        </article>

        <article class="card-surface detail-card">
            <h3>Berkas</h3>
            <dl class="detail-list">
                <div>
                    <dt>KTP</dt>
                    <dd><span
                            class="pill {{ $intern->ktp_path ? 'pill-success' : '' }}">{{ $intern->ktp_path ? 'Sudah upload' : 'Belum upload' }}</span>
                    </dd>
                </div>
                <div>
                    <dt>Kartu Siswa/Mahasiswa</dt>
                    <dd><span
                            class="pill {{ $intern->student_card_path ? 'pill-success' : '' }}">{{ $intern->student_card_path ? 'Sudah upload' : 'Belum upload' }}</span>
                    </dd>
                </div>
                <div>
                    <dt>BPJS Ketenagakerjaan</dt>
                    <dd><span
                            class="pill {{ $intern->bpjs_path ? 'pill-success' : '' }}">{{ $intern->bpjs_path ? 'Sudah upload' : 'Belum upload' }}</span>
                    </dd>
                </div>
            </dl>
        </article>
    </section>
@endsection
