@extends('layouts.app')

@section('title', 'Edit Permission')

@section('content')
    <div class="page-intro card-surface">
        <div>
            <p class="eyebrow">System Management</p>
            <h2>Edit Permission</h2>
        </div>
        <a href="{{ route('permissions.index') }}" class="pill">Kembali</a>
    </div>

    <div class="card-surface" style="margin-top: 1.5rem; padding: 2rem;">
        <form action="{{ route('permissions.update', $permission) }}" method="POST" class="auth-form">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nama Permission</label>
                <input type="text" name="name" id="name" value="{{ old('name', $permission->name) }}" required>
                @error('name') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="button" style="margin-top: 1rem;">Perbarui Permission</button>
        </form>
    </div>
@endsection
