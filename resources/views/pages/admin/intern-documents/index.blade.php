@extends('layouts.app')

@section('title', 'Berkas Intern')
@section('page_heading', 'Berkas Intern')

@section('content')
    <section class="page-intro card-surface">
        <div>
            <p class="eyebrow">Admin Area</p>
            <h2>Berkas Intern</h2>
            <p>Pantau kelengkapan KTP, kartu siswa/mahasiswa, dan BPJS Ketenagakerjaan.</p>
        </div>
        <a href="{{ route('admin.interns.index') }}" class="button button-muted">Data Intern</a>
    </section>

    <section class="card-surface table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Divisi</th>
                    <th>KTP</th>
                    <th>Kartu Siswa/Mahasiswa</th>
                    <th>BPJS</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($interns as $intern)
                    <tr>
                        <td>{{ $intern->name }}</td>
                        <td>{{ $intern->division?->name ?? '-' }}</td>
                        <td>{{ $intern->ktp_path ? 'Sudah' : 'Belum' }}</td>
                        <td>{{ $intern->student_card_path ? 'Sudah' : 'Belum' }}</td>
                        <td>{{ $intern->bpjs_path ? 'Sudah' : 'Belum' }}</td>
                        <td><span class="pill {{ $intern->hasCompletedDocuments() ? 'pill-success' : '' }}">{{ $intern->hasCompletedDocuments() ? 'Lengkap' : 'Belum lengkap' }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Belum ada data intern.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-wrap">
            {{ $interns->links() }}
        </div>
    </section>
@endsection
