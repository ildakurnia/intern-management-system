@extends('layouts.app')

@section('title', 'Upload Berkas')
@section('page_heading', 'Upload Berkas')

@section('content')
    <section class="page-intro card-surface">
        <div>
            <p class="eyebrow">{{ $intern->hasCompletedDocuments() ? 'Pengaturan Akun' : 'Intern Onboarding' }}</p>
            <h2>{{ $intern->hasCompletedDocuments() ? 'Edit Berkas' : 'Upload Berkas Wajib' }}</h2>
            <p>{{ $intern->hasCompletedDocuments() ? 'Perbarui berkas KTP, kartu pelajar/mahasiswa, atau BPJS Ketenagakerjaan Anda jika diperlukan.' : 'Upload KTP, kartu siswa/mahasiswa, dan BPJS Ketenagakerjaan agar dashboard terbuka.' }}</p>
        </div>
        @if(!$intern->hasCompletedDocuments())
        <span class="intro-badge">Langkah 2 dari 2</span>
        @endif
    </section>

    <section class="card-surface form-card">
        <form action="{{ route('intern.documents.update') }}" method="POST" enctype="multipart/form-data" class="auth-form">
            @csrf
            @method('PUT')

            <div class="document-list">
                <div class="document-row">
                    <div>
                        <h3>KTP</h3>
                        <p>{{ $intern->ktp_path ? 'Sudah upload. Upload file baru jika ingin mengganti.' : 'Belum upload.' }}</p>
                    </div>
                    <div class="form-group">
                        <input type="file" name="ktp" accept=".jpg,.jpeg,.png,.pdf" {{ $intern->ktp_path ? '' : 'required' }}>
                        @error('ktp') <small class="form-error">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="document-row">
                    <div>
                        <h3>Kartu Siswa/Mahasiswa</h3>
                        <p>{{ $intern->student_card_path ? 'Sudah upload. Upload file baru jika ingin mengganti.' : 'Belum upload.' }}</p>
                    </div>
                    <div class="form-group">
                        <input type="file" name="student_card" accept=".jpg,.jpeg,.png,.pdf" {{ $intern->student_card_path ? '' : 'required' }}>
                        @error('student_card') <small class="form-error">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="document-row">
                    <div>
                        <h3>BPJS Ketenagakerjaan</h3>
                        <p>{{ $intern->bpjs_path ? 'Sudah upload. Upload file baru jika ingin mengganti.' : 'Belum upload.' }}</p>
                    </div>
                    <div class="form-group">
                        <input type="file" name="bpjs" accept=".jpg,.jpeg,.png,.pdf" {{ $intern->bpjs_path ? '' : 'required' }}>
                        @error('bpjs') <small class="form-error">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="button">Simpan Berkas</button>
        </form>
    </section>
@endsection
