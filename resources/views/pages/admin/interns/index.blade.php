@extends('layouts.app')

@section('title', 'Data Intern')
@section('page_heading', 'Data Intern')

@section('content')
    <section class="page-intro card-surface">
        <div>
            <p class="eyebrow">Admin Area</p>
            <h2>Data Intern</h2>
            <p>Data awal dari import Excel dan status onboarding intern.</p>
        </div>
        @can('admin.interns.import')
            <a href="{{ route('admin.interns.import') }}" class="button">Import Excel</a>
        @endcan
    </section>

    @if (session('import_errors'))
        <section class="alert alert-warning">
            <strong>Catatan import:</strong>
            <ul>
                @foreach (session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </section>
    @endif

    <section class="card-surface table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>NIM/NIS</th>
                    <th>Divisi</th>
                    <th>Registrasi</th>
                    <th>Profil</th>
                    <th>Berkas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($interns as $intern)
                    <tr>
                        <td>{{ $intern->name }}</td>
                        <td>{{ $intern->email }}</td>
                        <td>{{ $intern->nim ?? $intern->nis }}</td>
                        <td>{{ $intern->division?->name ?? '-' }}</td>
                        <td><span class="pill">{{ $intern->registration_status }}</span></td>
                        <td><span class="pill {{ $intern->hasCompletedProfile() ? 'pill-success' : '' }}">{{ $intern->hasCompletedProfile() ? 'Lengkap' : 'Belum' }}</span></td>
                        <td><span class="pill {{ $intern->hasCompletedDocuments() ? 'pill-success' : '' }}">{{ $intern->hasCompletedDocuments() ? 'Lengkap' : 'Belum' }}</span></td>
                        <td>
                            @can('admin.interns.show')
                                <a href="{{ route('admin.interns.show', $intern) }}" class="pill pill-primary">Detail</a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">Belum ada data intern.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-wrap">
            {{ $interns->links() }}
        </div>
    </section>
@endsection
