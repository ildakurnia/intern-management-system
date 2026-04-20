@extends('layouts.app')

@section('title', 'Tambah Logbook')

@section('content')
    <section class="page-intro card-surface">
        <div>
            <p class="eyebrow">Aktivitas Magang</p>
            <h2>Tambah Logbook</h2>
            <p>Isi laporan kegiatan harian dengan ringkas, jelas, dan sesuai pekerjaan yang dilakukan.</p>
        </div>
        <a href="{{ route('intern.logbooks.index') }}" class="button button-muted">Kembali</a>
    </section>

    <section class="card-surface form-card">
        <form action="{{ route('intern.logbooks.store') }}" method="POST" class="auth-form form-grid">
            @csrf

            <div class="form-group form-span">
                <label for="tanggal">Tanggal</label>
                <input id="tanggal" type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}" max="{{ now()->toDateString() }}" required>
                @error('tanggal') <small class="form-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-group form-span">
                <label for="uraian_aktivitas">Uraian aktivitas</label>
                <textarea id="uraian_aktivitas" name="uraian_aktivitas" rows="5" placeholder="Tulis uraian aktivitas minimal 100 karakter" required>{{ old('uraian_aktivitas') }}</textarea>
                @error('uraian_aktivitas') <small class="form-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-group form-span">
                <label for="pembelajaran_diperoleh">Pembelajaran yang diperoleh</label>
                <textarea id="pembelajaran_diperoleh" name="pembelajaran_diperoleh" rows="5" placeholder="Tulis ilmu/pembelajaran yang diperoleh minimal 100 karakter" required>{{ old('pembelajaran_diperoleh') }}</textarea>
                @error('pembelajaran_diperoleh') <small class="form-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-group form-span">
                <label for="kendala_dialami">Kendala yang dialami</label>
                <textarea id="kendala_dialami" name="kendala_dialami" rows="5" placeholder="Tulis kendala/hambatan yang dialami. Jika tidak ada, boleh dikosongkan.">{{ old('kendala_dialami') }}</textarea>
                @error('kendala_dialami') <small class="form-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-span">
                <label class="checkbox">
                    <input type="checkbox" name="confirmation" value="1" @checked(old('confirmation')) required>
                    <span>Saya menyatakan telah meninjau dan memastikan isian logbook ini sudah benar.</span>
                </label>
                @error('confirmation') <small class="form-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-span">
                <button type="submit" class="button">Simpan dan Kirim</button>
            </div>
        </form>
    </section>
@endsection
