@extends('layouts.app')

@section('title', 'Roles Management')

@section('content')
    <div class="settings-page-head">
        <div>
            <h1>Roles</h1>
            <div class="breadcrumb-line">
                <span>Settings</span>
                <span>&rsaquo;</span>
                <span>Roles</span>
            </div>
        </div>

        <div class="settings-page-actions">
            <label class="settings-search" aria-label="Search roles">
                <span>
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M9.5 3a6.5 6.5 0 0 1 5.15 10.46l4.44 4.45-1.42 1.41-4.44-4.44A6.5 6.5 0 1 1 9.5 3Zm0 2a4.5 4.5 0 1 0 0 9 4.5 4.5 0 0 0 0-9Z"/>
                    </svg>
                </span>
                <input type="search" placeholder="Search" data-role-search>
            </label>

            <a href="{{ route('roles.create') }}" class="button button-success">+ Create new</a>
        </div>
    </div>

    <div class="settings-table-card">
        <table class="settings-table" data-role-table>
            <thead>
                <tr>
                    <th class="settings-col-number">No</th>
                    <th>Action</th>
                    <th>Name</th>
                    <th>Guard name</th>
                    <th>Create Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr data-role-row>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="settings-icon-actions">
                                <a href="{{ route('roles.edit', $role) }}" class="icon-action icon-action-edit" aria-label="Edit {{ $role->name }}" title="Edit">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M5 19h1.42L16.2 9.22 14.78 7.8 5 17.58V19Zm-2 2v-4.25L16.2 3.55a2 2 0 0 1 2.83 0l1.42 1.42a2 2 0 0 1 0 2.83L7.25 21H3Zm14.62-13.2 1.42-1.42-1.42-1.42-1.42 1.42 1.42 1.42Z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Hapus role ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="icon-action icon-action-delete" aria-label="Hapus {{ $role->name }}" title="Hapus">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M7 21a2 2 0 0 1-2-2V7H4V5h5V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1h5v2h-1v12a2 2 0 0 1-2 2H7ZM17 7H7v12h10V7Zm-6 10H9V9h2v8Zm4 0h-2V9h2v8ZM11 5h2v-.5h-2V5Z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->guard_name }}</td>
                        <td>{{ $role->created_at?->format('Y-m-d') ?? '-' }}</td>
                    </tr>
                @endforeach

                @if ($roles->isEmpty())
                    <tr>
                        <td colspan="5">Belum ada role.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
