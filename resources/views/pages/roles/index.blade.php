@extends('layouts.app')

@section('title', 'Roles Management')

@section('content')
    <div class="page-intro card-surface">
        <div>
            <p class="eyebrow">System Management</p>
            <h2>Role Access List</h2>
            <p>Kelola daftar peran pengguna dan fungsionalitasnya.</p>
        </div>
        <a href="{{ route('roles.create') }}" class="button">+ Tambah Role</a>
    </div>

    <div class="card-surface mt-4" style="margin-top: 1.5rem; padding: 1.5rem;">
        <table style="width: 100%; text-align: left; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th style="padding: 1rem 0;">Nama Role</th>
                    <th style="padding: 1rem 0;">Akses Permission</th>
                    <th style="padding: 1rem 0; width: 200px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 1rem 0; font-weight: 600;">{{ $role->name }}</td>
                        <td style="padding: 1rem 0;">
                            @foreach ($role->permissions as $permission)
                                <span class="pill pill-primary" style="margin: 0.2rem; font-size: 0.75rem;">{{ $permission->name }}</span>
                            @endforeach
                        </td>
                        <td style="padding: 1rem 0; display: flex; gap: 0.5rem;">
                            <a href="{{ route('roles.edit', $role) }}" class="pill" style="background: var(--primary-soft); color: var(--primary);">Edit</a>
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Hapus role ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="pill" style="background: #ffe5e5; color: #ea5455; border: none; cursor: pointer;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
