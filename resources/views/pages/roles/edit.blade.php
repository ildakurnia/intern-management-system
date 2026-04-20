@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
    <div class="settings-page-head">
        <div>
            <h1>Edit</h1>
            <div class="breadcrumb-line">
                <span>Settings</span>
                <span>&rsaquo;</span>
                <span>Roles</span>
                <span>&rsaquo;</span>
                <span>Edit</span>
            </div>
        </div>

        <a href="{{ route('roles.index') }}" class="button button-blue">
            <span>&lsaquo;</span>
            Back
        </a>
    </div>

    <div class="settings-form-card">
        <form action="{{ route('roles.update', $role) }}" method="POST" class="auth-form">
            @csrf
            @method('PUT')
            <div class="settings-form-grid">
                <div class="form-group">
                    <label for="name">Role name <span class="required-mark">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required>
                    @error('name') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="guard_name">Guard name <span class="required-mark">*</span></label>
                    <input type="text" name="guard_name" id="guard_name" value="{{ old('guard_name', $role->guard_name) }}" required>
                    @error('guard_name') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            @include('pages.roles._permission-groups')

            <div class="form-actions">
                <button type="submit" class="button button-success">Perbarui Role</button>
            </div>
        </form>
    </div>
@endsection
