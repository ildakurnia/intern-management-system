@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
    <div class="page-intro card-surface">
        <div>
            <p class="eyebrow">System Management</p>
            <h2>Edit Role</h2>
        </div>
        <a href="{{ route('roles.index') }}" class="pill">Kembali</a>
    </div>

    <div class="card-surface" style="margin-top: 1.5rem; padding: 2rem;">
        <form action="{{ route('roles.update', $role) }}" method="POST" class="auth-form">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nama Role</label>
                <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required>
                @error('name') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-top: 1rem;">
                <label>Tetapkan Permissions</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-top: 0.5rem;">
                    @foreach ($permissions as $permission)
                        <label class="checkbox" style="cursor: pointer;">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                            {{ $permission->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="button" style="margin-top: 1rem;">Perbarui Role</button>
        </form>
    </div>
@endsection
