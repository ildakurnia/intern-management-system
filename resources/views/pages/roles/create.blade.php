@extends('layouts.app')

@section('title', 'Tambah Role')

@section('content')
    <div class="settings-page-head">
        <div>
            <h1>Create</h1>
            <div class="breadcrumb-line">
                <span>Settings</span>
                <span>&rsaquo;</span>
                <span>Roles</span>
                <span>&rsaquo;</span>
                <span>Create</span>
            </div>
        </div>

        <a href="{{ route('roles.index') }}" class="button button-blue">
            <span>&lsaquo;</span>
            Back
        </a>
    </div>

    <div class="settings-form-card">
        <form action="{{ route('roles.store') }}" method="POST" class="auth-form">
            @csrf
            <div class="settings-form-grid">
                <div class="form-group">
                    <label for="name">Role name <span class="required-mark">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Misal: hrd, mentor, intern" required>
                    @error('name') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="guard_name">Guard name <span class="required-mark">*</span></label>
                    <input type="text" name="guard_name" id="guard_name" value="{{ old('guard_name', 'web') }}" required>
                    @error('guard_name') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            @include('pages.roles._permission-groups')

            <div class="form-actions">
                <button type="submit" class="button button-success">Simpan Role</button>
            </div>
        </form>
    </div>
@endsection
