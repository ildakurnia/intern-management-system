@extends('layouts.app')

@section('title', 'Permissions Management')

@section('content')
    <div class="page-intro card-surface">
        <div>
            <p class="eyebrow">System Management</p>
            <h2>Permission List</h2>
            <p>Daftar izin spesifik untuk setiap fitur aplikasi.</p>
        </div>
        <a href="{{ route('permissions.create') }}" class="button">+ Tambah Permission</a>
    </div>

    <div class="card-surface mt-4" style="margin-top: 1.5rem; padding: 1.5rem;">
        <div style="display: flex; gap: 0.875rem; flex-wrap: wrap;">
            @foreach ($permissions as $permission)
                <div class="pill" style="display: flex; align-items: center; gap: 0.875rem;">
                    <span>{{ $permission->name }}</span>
                    
                    <a href="{{ route('permissions.edit', $permission) }}" style="color: var(--primary);">✎</a>
                    <form action="{{ route('permissions.destroy', $permission) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: #ea5455; cursor: pointer; padding: 0;">&times;</button>
                    </form>
                </div>
            @endforeach
            
            @if($permissions->isEmpty())
                <p>Belum ada daftar permission.</p>
            @endif
        </div>
    </div>
@endsection
