@extends('layouts.app')

@section('title', 'Detail Intern')
@section('page_heading', 'Detail Intern')

@php
    $identifierLabel = $intern->type === 'mahasiswa' ? 'NIM' : 'NIS';
    $identifierValue = $intern->type === 'mahasiswa' ? $intern->nim : $intern->nis;
@endphp

@section('content')
    <style>
        @media (max-width: 767.98px) {
            .page-intro {
                display: none;
            }

            .intern-detail-desktop {
                display: none;
            }

            .intern-detail-mobile {
                display: grid;
                gap: 1rem;
            }

            .intern-mobile-hero {
                padding: 1rem;
                display: grid;
                gap: 0.85rem;
            }

            .intern-mobile-head {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 0.75rem;
            }

            .intern-mobile-name {
                margin: 0;
                font-size: 1.15rem;
                line-height: 1.25;
                font-weight: 800;
                color: var(--body-color, #1f2744);
            }

            .intern-mobile-sub {
                margin-top: 0.2rem;
                font-size: 0.9rem;
                color: var(--text-muted, #8d93ac);
                word-break: break-word;
            }

            .intern-mobile-badges {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .intern-mobile-badges .pill {
                display: inline-flex;
                align-items: center;
                padding-inline: 0.75rem;
            }

            .intern-mobile-actions {
                display: grid;
                gap: 0.55rem;
            }

            .intern-mobile-actions .button,
            .intern-mobile-actions .btn {
                width: 100%;
                justify-content: center;
            }

            .intern-mobile-section {
                padding: 1rem;
            }

            .intern-mobile-section h3 {
                margin: 0;
                font-size: 1rem;
                font-weight: 800;
                color: var(--body-color, #1f2744);
            }

            .intern-mobile-section-head {
                display: flex;
                align-items: center;
                gap: 0.6rem;
                margin-bottom: 0.9rem;
            }

            .intern-card-heading {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                margin: 0;
                font-size: 1rem;
                font-weight: 800;
                color: var(--body-color, #1f2744);
            }

            .intern-card-heading i {
                color: #4338ca;
                font-size: 1.05rem;
            }

            .intern-mobile-section-head .intern-card-heading {
                margin: 0;
            }

            .intern-mobile-kv {
                display: grid;
                gap: 0.2rem;
            }

            .intern-mobile-kv-item {
                display: flex;
                align-items: flex-start;
                gap: 0.7rem;
                padding: 0.7rem 0;
                border-bottom: 1px solid rgba(90, 96, 141, 0.12);
            }

            .intern-mobile-kv-item:last-child {
                padding-bottom: 0;
                border-bottom: 0;
            }

            .intern-mobile-kv-icon {
                width: 2rem;
                height: 2rem;
                border-radius: 999px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                background: rgba(67, 56, 202, 0.08);
                color: #4338ca;
            }

            .intern-mobile-kv-label {
                display: block;
                font-size: 0.76rem;
                text-transform: uppercase;
                letter-spacing: 0.04em;
                color: var(--text-muted, #8d93ac);
                margin-bottom: 0.2rem;
            }

            .intern-mobile-kv-value {
                font-size: 0.94rem;
                font-weight: 600;
                color: var(--body-color, #1f2744);
                word-break: break-word;
            }

            .intern-mobile-stack {
                display: grid;
                gap: 0;
            }

            .intern-mobile-status {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.75rem;
                padding: 0.8rem 0;
                border-bottom: 1px solid rgba(90, 96, 141, 0.12);
            }

            .intern-mobile-status:last-child {
                border-bottom: 0;
                padding-bottom: 0;
            }

            .intern-mobile-status span {
                color: var(--text-muted, #8d93ac);
                font-size: 0.9rem;
            }

            .intern-mobile-status .pill {
                white-space: nowrap;
            }
        }

        @media (min-width: 768px) {
            .intern-detail-mobile {
                display: none;
            }
        }
    </style>

    <section class="page-intro card-surface">
        <div>
            <p class="eyebrow">Admin Area</p>
            <h2>{{ $intern->name }}</h2>
            <p>Detail biodata, status onboarding, dan kelengkapan berkas intern.</p>
        </div>
        <a href="{{ route('admin.interns.index') }}" class="button button-muted">Kembali</a>
    </section>

    <section class="intern-detail-mobile">
        <article class="card-surface intern-mobile-hero">
            <div class="intern-mobile-head">
                <div class="min-w-0">
                    <h2 class="intern-mobile-name">{{ $intern->name }}</h2>
                    <div class="intern-mobile-sub">{{ $intern->email ?? '-' }}</div>
                </div>
                <span class="pill {{ $intern->user ? 'pill-success' : '' }}">
                    {{ $intern->user ? 'Terhubung' : 'Belum registrasi' }}
                </span>
            </div>

            <div class="intern-mobile-badges">
                <span class="pill">{{ ucfirst($intern->type) }}</span>
                <span class="pill">{{ $intern->division?->name ?? 'Tanpa Divisi' }}</span>
                <span class="pill {{ $intern->status === 'active' ? 'pill-success' : '' }}">
                    {{ ucfirst($intern->status) }}
                </span>
            </div>

            <div class="intern-mobile-actions">
                <a href="{{ route('admin.interns.index') }}" class="button button-muted">Kembali</a>
            </div>
        </article>

        <article class="card-surface intern-mobile-section">
            <div class="intern-mobile-section-head">
                <span class="intern-card-heading"><i class="ri ri-id-card-line"></i>Data Dasar</span>
            </div>
            <div class="intern-mobile-kv">
                <div class="intern-mobile-kv-item">
                    <div>
                        <span class="intern-mobile-kv-label">Nama</span>
                        <div class="intern-mobile-kv-value">{{ $intern->name }}</div>
                    </div>
                </div>
                <div class="intern-mobile-kv-item">
                    <div>
                        <span class="intern-mobile-kv-label">Email</span>
                        <div class="intern-mobile-kv-value">{{ $intern->email ?? '-' }}</div>
                    </div>
                </div>
                <div class="intern-mobile-kv-item">
                    <div>
                        <span class="intern-mobile-kv-label">{{ $identifierLabel }}</span>
                        <div class="intern-mobile-kv-value">{{ $identifierValue ?? '-' }}</div>
                    </div>
                </div>
                <div class="intern-mobile-kv-item">
                    <div>
                        <span class="intern-mobile-kv-label">Periode Magang</span>
                        <div class="intern-mobile-kv-value">
                            {{ optional($intern->start_date)->format('d M Y') }} - {{ optional($intern->end_date)->format('d M Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <article class="card-surface intern-mobile-section">
            <div class="intern-mobile-section-head">
                <span class="intern-card-heading"><i class="ri ri-shield-star-line"></i>Status Onboarding</span>
            </div>
            <div class="intern-mobile-stack">
                <div class="intern-mobile-status">
                    <span>Registrasi</span>
                    <strong class="pill {{ $intern->registration_status === 'registered' ? 'pill-success' : '' }}">{{ $intern->registration_status }}</strong>
                </div>
                <div class="intern-mobile-status">
                    <span>Profil</span>
                    <strong class="pill {{ $intern->hasCompletedProfile() ? 'pill-success' : '' }}">{{ $intern->hasCompletedProfile() ? 'Lengkap' : 'Belum' }}</strong>
                </div>
                <div class="intern-mobile-status">
                    <span>Berkas</span>
                    <strong class="pill {{ $intern->hasCompletedDocuments() ? 'pill-success' : '' }}">{{ $intern->hasCompletedDocuments() ? 'Lengkap' : 'Belum' }}</strong>
                </div>
            </div>
        </article>

        <article class="card-surface intern-mobile-section">
            <div class="intern-mobile-section-head">
                <span class="intern-card-heading"><i class="ri ri-user-settings-line"></i>Biodata</span>
            </div>
            <div class="intern-mobile-kv">
                <div class="intern-mobile-kv-item">
                    <div>
                        <span class="intern-mobile-kv-label">No HP</span>
                        <div class="intern-mobile-kv-value">{{ $intern->phone ?? '-' }}</div>
                    </div>
                </div>
                <div class="intern-mobile-kv-item">
                    <div>
                        <span class="intern-mobile-kv-label">Tanggal Lahir</span>
                        <div class="intern-mobile-kv-value">{{ optional($intern->birth_date)->format('d M Y') ?? '-' }}</div>
                    </div>
                </div>
                <div class="intern-mobile-kv-item">
                    <div>
                        <span class="intern-mobile-kv-label">Jenis Kelamin</span>
                        <div class="intern-mobile-kv-value">{{ $intern->gender === 'male' ? 'Laki-laki' : ($intern->gender === 'female' ? 'Perempuan' : '-') }}</div>
                    </div>
                </div>
                <div class="intern-mobile-kv-item">
                    <div>
                        <span class="intern-mobile-kv-label">Asal Sekolah/Kampus</span>
                        <div class="intern-mobile-kv-value">{{ $intern->institution ?? '-' }}</div>
                    </div>
                </div>
                <div class="intern-mobile-kv-item">
                    <div>
                        <span class="intern-mobile-kv-label">Jurusan</span>
                        <div class="intern-mobile-kv-value">{{ $intern->major ?? '-' }}</div>
                    </div>
                </div>
                <div class="intern-mobile-kv-item">
                    <div>
                        <span class="intern-mobile-kv-label">Alamat</span>
                        <div class="intern-mobile-kv-value">{{ $intern->address ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </article>

        <article class="card-surface intern-mobile-section">
            <div class="intern-mobile-section-head">
                <span class="intern-card-heading"><i class="ri ri-attachment-2"></i>Berkas</span>
            </div>
            <div class="intern-mobile-stack">
                <div class="intern-mobile-status">
                    <span>KTP</span>
                    <strong class="pill {{ $intern->ktp_path ? 'pill-success' : '' }}">{{ $intern->ktp_path ? 'Sudah upload' : 'Belum upload' }}</strong>
                </div>
                <div class="intern-mobile-status">
                    <span>Kartu Siswa/Mahasiswa</span>
                    <strong class="pill {{ $intern->student_card_path ? 'pill-success' : '' }}">{{ $intern->student_card_path ? 'Sudah upload' : 'Belum upload' }}</strong>
                </div>
                <div class="intern-mobile-status">
                    <span>BPJS Ketenagakerjaan</span>
                    <strong class="pill {{ $intern->bpjs_path ? 'pill-success' : '' }}">{{ $intern->bpjs_path ? 'Sudah upload' : 'Belum upload' }}</strong>
                </div>
            </div>
        </article>
    </section>

    <section class="intern-detail-desktop">
        <section class="detail-grid">
            <article class="card-surface detail-card">
                <h3 class="intern-card-heading"><i class="ri ri-id-card-line"></i>Data Dasar</h3>
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
                        <dt>Periode Magang</dt>
                        <dd>{{ optional($intern->start_date)->format('d M Y') }} - {{ optional($intern->end_date)->format('d M Y') }}</dd>
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
                <h3 class="intern-card-heading"><i class="ri ri-shield-star-line"></i>Status Onboarding</h3>
                <div class="status-stack">
                    <div class="status-item">
                        <span>Registrasi</span>
                        <strong class="pill {{ $intern->registration_status === 'registered' ? 'pill-success' : '' }}">{{ $intern->registration_status }}</strong>
                    </div>
                    <div class="status-item">
                        <span>Profil</span>
                        <strong class="pill {{ $intern->hasCompletedProfile() ? 'pill-success' : '' }}">{{ $intern->hasCompletedProfile() ? 'Lengkap' : 'Belum' }}</strong>
                    </div>
                    <div class="status-item">
                        <span>Berkas</span>
                        <strong class="pill {{ $intern->hasCompletedDocuments() ? 'pill-success' : '' }}">{{ $intern->hasCompletedDocuments() ? 'Lengkap' : 'Belum' }}</strong>
                    </div>
                </div>
            </article>
        </section>

        <section class="detail-grid detail-grid-wide">
            <article class="card-surface detail-card">
                <h3 class="intern-card-heading"><i class="ri ri-user-settings-line"></i>Biodata</h3>
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
                        <dd>{{ $intern->gender === 'male' ? 'Laki-laki' : ($intern->gender === 'female' ? 'Perempuan' : '-') }}</dd>
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
                <h3 class="intern-card-heading"><i class="ri ri-attachment-2"></i>Berkas</h3>
                <dl class="detail-list">
                    <div>
                        <dt>KTP</dt>
                        <dd><span class="pill {{ $intern->ktp_path ? 'pill-success' : '' }}">{{ $intern->ktp_path ? 'Sudah upload' : 'Belum upload' }}</span></dd>
                    </div>
                    <div>
                        <dt>Kartu Siswa/Mahasiswa</dt>
                        <dd><span class="pill {{ $intern->student_card_path ? 'pill-success' : '' }}">{{ $intern->student_card_path ? 'Sudah upload' : 'Belum upload' }}</span></dd>
                    </div>
                    <div>
                        <dt>BPJS Ketenagakerjaan</dt>
                        <dd><span class="pill {{ $intern->bpjs_path ? 'pill-success' : '' }}">{{ $intern->bpjs_path ? 'Sudah upload' : 'Belum upload' }}</span></dd>
                    </div>
                </dl>
            </article>
        </section>
    </section>
@endsection
