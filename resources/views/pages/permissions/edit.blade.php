@extends('layouts.app')

@section('title', 'Edit Permission')

@section('content')
    <div class="settings-page-head">
        <div>
            <h1>Edit</h1>
            <div class="breadcrumb-line">
                <span>Settings</span>
                <span>&rsaquo;</span>
                <span>Permissions</span>
                <span>&rsaquo;</span>
                <span>Edit</span>
            </div>
        </div>

        <a href="{{ route('permissions.index') }}" class="button button-blue">
            <span>&lsaquo;</span>
            Back
        </a>
    </div>

    <div class="settings-form-card">
        <form action="{{ route('permissions.update', $permission) }}" method="POST" class="auth-form">
            @csrf
            @method('PUT')
            <div class="settings-form-grid">
                <div class="form-group">
                    <label for="name">Route Permission <span class="required-mark">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $permission->name) }}" required>
                    @error('name') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="guard_name">Guard name <span class="required-mark">*</span></label>
                    <input type="text" name="guard_name" id="guard_name" value="{{ old('guard_name', $permission->guard_name) }}" required>
                    @error('guard_name') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="menu_id">Menu Fitur</label>
                    <select name="menu_id" id="menu_id">
                        <option value="">Tanpa menu</option>
                        @foreach ($menus as $menu)
                            <option value="{{ $menu->id }}" @selected((string) old('menu_id', $permission->menu_id) === (string) $menu->id)>
                                {{ $menu->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('menu_id') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="sort_order">Urutan</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $permission->sort_order ?? 0) }}" min="0">
                    @error('sort_order') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group form-span">
                    <label for="label">Label UI</label>
                    <input type="text" name="label" id="label" value="{{ old('label', $permission->label) }}" placeholder="Misal: Read List">
                    @error('label') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="button button-success">Perbarui Permission</button>
            </div>
        </form>
    </div>
@endsection
