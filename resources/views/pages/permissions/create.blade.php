@extends('layouts.app')

@section('title', 'Tambah Permission')

@section('content')
    <div class="page-intro card-surface">
        <div>
            <p class="eyebrow">System Management</p>
            <h2>Tambah Permission Baru</h2>
        </div>
        <a href="{{ route('permissions.index') }}" class="pill">Kembali</a>
    </div>

    <div class="card-surface" style="margin-top: 1.5rem; padding: 2rem;">
        <form action="{{ route('permissions.store') }}" method="POST" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="name">Nama Permission</label>
                <input type="text" name="name" id="name" placeholder="Misal: create users, open reports" required>
                @error('name') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="button" style="margin-top: 1rem;">Simpan Permission</button>
        </form>
    </div>
@endsection
