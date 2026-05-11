@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Role')

@section('page-style')
<style>
.permission-table { width: 100%; border-collapse: collapse; }
.permission-table th {
  padding: 10px 14px;
  background: rgba(var(--bs-primary-rgb), 0.08);
  color: var(--bs-heading-color);
  font-weight: 600;
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: .05em;
}
.permission-table td { padding: 8px 14px; vertical-align: middle; border-bottom: 1px solid var(--bs-border-color); }
.permission-group-header td {
  background: rgba(var(--bs-primary-rgb), 0.05);
  font-weight: 600;
  color: var(--bs-primary);
  padding: 10px 14px;
  border-top: 2px solid rgba(var(--bs-primary-rgb), 0.15);
}
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-1">Edit Role: {{ ucfirst($role->name) }}</h4>
    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="ri-arrow-left-line me-1"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-6">
                <label class="form-label fw-semibold" for="roleName">Nama Role</label>
                <input type="text" id="roleName" name="name" class="form-control" 
                    placeholder="Contoh: manager" value="{{ old('name', $role->name) }}" required />
                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">Akses Permission</h5>
                <div class="d-flex align-items-center gap-2">
                    <label class="form-check-label text-body-secondary small" for="selectAll">Pilih Semua</label>
                    <div class="form-check form-switch form-switch-lg mb-0">
                        <input class="form-check-input" type="checkbox" id="selectAll" />
                    </div>
                </div>
            </div>

            <div class="table-responsive border rounded mb-6">
                <table class="permission-table">
                    <thead>
                        <tr>
                            <th style="width:70%">Menu / Fitur</th>
                            <th class="text-center">Akses</th>
                        </tr>
                    </thead>
                    @foreach ($permissionMenus as $menu)
                    @php
                        $menuPermNames = $menu->permissions->pluck('name')->toArray();
                        $allChecked = count($menuPermNames) > 0 && count(array_intersect($menuPermNames, $rolePermissions)) === count($menuPermNames);
                    @endphp
                    <tbody>
                        <tr class="permission-group-header">
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-folder-line icon-16px"></i>
                                    {{ $menu->title }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center mb-0">
                                    <input class="form-check-input group-toggle" type="checkbox" 
                                        data-group="{{ str_replace(' ', '-', strtolower($menu->title)) }}"
                                        @checked($allChecked) />
                                </div>
                            </td>
                        </tr>
                        @foreach ($menu->permissions as $permission)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2 ps-3">
                                    <i class="ri-arrow-right-s-line text-body-secondary"></i>
                                    <span>{{ $permission->label ?? $permission->name }}</span>
                                    <code class="small text-body-secondary ms-1">{{ $permission->name }}</code>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center mb-0">
                                    <input class="form-check-input perm-toggle" type="checkbox" 
                                        name="permissions[]" value="{{ $permission->name }}"
                                        data-group-parent="{{ str_replace(' ', '-', strtolower($menu->title)) }}"
                                        @checked(in_array($permission->name, old('permissions', $rolePermissions))) />
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @endforeach
                </table>
            </div>

            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">Perbarui Role</button>
                <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAll');
    const groupToggles = document.querySelectorAll('.group-toggle');
    const permToggles = document.querySelectorAll('.perm-toggle');

    // Initial check for Global Select All
    updateGlobalSelectAll();

    // Select All Toggle
    selectAll.addEventListener('change', function() {
        permToggles.forEach(t => t.checked = this.checked);
        groupToggles.forEach(t => t.checked = this.checked);
    });

    // Group Toggle (On/Off per Menu)
    groupToggles.forEach(gt => {
        gt.addEventListener('change', function() {
            const group = this.dataset.group;
            document.querySelectorAll(`[data-group-parent="${group}"]`).forEach(t => {
                t.checked = this.checked;
            });
            updateGlobalSelectAll();
        });
    });

    // Individual Toggle
    permToggles.forEach(t => {
        t.addEventListener('change', function() {
            const group = this.dataset.groupParent;
            const groupToggle = document.querySelector(`.group-toggle[data-group="${group}"]`);
            const sameGroupPerms = document.querySelectorAll(`[data-group-parent="${group}"]`);
            
            groupToggle.checked = Array.from(sameGroupPerms).every(i => i.checked);
            updateGlobalSelectAll();
        });
    });

    function updateGlobalSelectAll() {
        if (selectAll) {
            selectAll.checked = permToggles.length > 0 && Array.from(permToggles).every(t => t.checked);
        }
    }
});
</script>
@endsection
