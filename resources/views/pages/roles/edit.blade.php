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

.permission-desktop-shell {
  display: block;
}

.permission-mobile-shell {
  display: grid;
  gap: 0.9rem;
}

.permission-mobile-card {
  border: 1px solid var(--bs-border-color);
  border-radius: 1rem;
  background: var(--bs-card-bg);
  overflow: hidden;
}

.permission-mobile-head {
  display: grid;
  gap: 0.7rem;
  padding: 1rem;
  list-style: none;
  cursor: pointer;
}

.permission-mobile-head::-webkit-details-marker {
  display: none;
}

.permission-mobile-head-left {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  min-width: 0;
}

.permission-mobile-head-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.75rem;
  font-size: 0.72rem;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  color: var(--bs-secondary-color);
  font-weight: 700;
}

.permission-mobile-head-main {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 0.75rem;
}

.permission-mobile-title-icon {
  width: 2rem;
  height: 2rem;
  border-radius: 999px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  background: rgba(var(--bs-primary-rgb), 0.1);
  color: var(--bs-primary);
}

.permission-mobile-title {
  margin: 0;
  font-size: 0.98rem;
  font-weight: 800;
  color: var(--bs-heading-color);
}

.permission-mobile-master {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.permission-mobile-master .form-check {
  margin: 0;
}

.permission-mobile-count {
  white-space: nowrap;
}

.permission-mobile-body {
  border-top: 1px solid var(--bs-border-color);
  padding: 0.95rem 1rem 1rem;
}

.permission-mobile-perm {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 0.9rem;
  padding: 0.75rem 0;
  border-bottom: 1px dashed var(--bs-border-color);
}

.permission-mobile-perm:last-child {
  border-bottom: 0;
  padding-bottom: 0;
}

.permission-mobile-perm-label {
  min-width: 0;
}

.permission-mobile-perm-label strong {
  display: block;
  font-size: 0.92rem;
  color: var(--bs-heading-color);
  word-break: break-word;
}

.permission-mobile-perm-label code {
  display: inline-block;
  margin-top: 0.2rem;
  font-size: 0.72rem;
  word-break: break-word;
}

.permission-mobile-perm .form-switch {
  flex-shrink: 0;
}

@media (max-width: 767.98px) {
  .permission-search-shell {
    width: 100%;
    min-width: 0;
  }
}
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 ims-mobile-toolbar">
    <h4 class="mb-1">Edit Role: {{ ucfirst($role->name) }}</h4>
    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="ri ri-arrow-left-line me-1"></i> Kembali
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

            <div class="table-responsive border rounded mb-6 permission-desktop-shell d-none d-md-block">
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
                                    <i class="ri ri-folder-line icon-16px"></i>
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
                                    <i class="ri ri-arrow-right-s-line text-body-secondary"></i>
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

            <div class="permission-mobile-shell d-md-none mb-6">
                @forelse ($permissionMenus as $menu)
                    @php
                        $groupKey = str_replace(' ', '-', strtolower($menu->title));
                        $menuPermNames = $menu->permissions->pluck('name')->toArray();
                        $allChecked = count($menuPermNames) > 0 && count(array_intersect($menuPermNames, $rolePermissions)) === count($menuPermNames);
                    @endphp
                    <details class="permission-mobile-card" @if($loop->first) open @endif>
                        <summary class="permission-mobile-head">
                            <div class="permission-mobile-head-top">
                                <span>Menu / Fitur</span>
                                <span>Akses</span>
                            </div>
                            <div class="permission-mobile-head-main">
                                <div class="permission-mobile-head-left">
                                    <span class="permission-mobile-title-icon">
                                        <i class="icon-base ri ri-folder-line"></i>
                                    </span>
                                    <div class="min-w-0">
                                        <h6 class="permission-mobile-title text-truncate">{{ $menu->title }}</h6>
                                        <small class="text-body-secondary">{{ $menu->permissions->count() }} permission</small>
                                    </div>
                                </div>
                                <div class="permission-mobile-master" onclick="event.stopPropagation()">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input group-toggle" type="checkbox"
                                            data-group="{{ $groupKey }}"
                                            @checked($allChecked) />
                                    </div>
                                </div>
                            </div>
                        </summary>

                        <div class="permission-mobile-body">
                            @foreach ($menu->permissions as $permission)
                                <div class="permission-mobile-perm">
                                    <div class="permission-mobile-perm-label">
                                        <strong>{{ $permission->label ?? $permission->name }}</strong>
                                        <code class="text-body-secondary">{{ $permission->name }}</code>
                                    </div>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input perm-toggle" type="checkbox"
                                            name="permissions[]" value="{{ $permission->name }}"
                                            data-group-parent="{{ $groupKey }}"
                                            @checked(in_array($permission->name, old('permissions', $rolePermissions))) />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </details>
                @empty
                    @if($unassignedPermissions->isEmpty())
                        <div class="permission-mobile-card text-center p-4">
                            <div class="card-body">
                                <i class="icon-base ri ri-shield-user-line icon-32px text-muted mb-2 d-block"></i>
                                <p class="mb-0">Belum ada permission tersedia.</p>
                            </div>
                        </div>
                    @endif
                @endforelse

                @if($unassignedPermissions->isNotEmpty())
                    @php
                        $groupKey = 'tanpa-menu';
                    @endphp
                    <details class="permission-mobile-card" open>
                        <summary class="permission-mobile-head">
                            <div class="permission-mobile-head-top">
                                <span>Menu / Fitur</span>
                                <span>Akses</span>
                            </div>
                            <div class="permission-mobile-head-main">
                                <div class="permission-mobile-head-left">
                                    <span class="permission-mobile-title-icon">
                                        <i class="icon-base ri ri-question-line"></i>
                                    </span>
                                    <div class="min-w-0">
                                        <h6 class="permission-mobile-title text-truncate">Tanpa Menu (Uncategorized)</h6>
                                        <small class="text-body-secondary">{{ $unassignedPermissions->count() }} permission</small>
                                    </div>
                                </div>
                                <div class="permission-mobile-master" onclick="event.stopPropagation()">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input group-toggle" type="checkbox"
                                            data-group="{{ $groupKey }}" />
                                    </div>
                                </div>
                            </div>
                        </summary>

                        <div class="permission-mobile-body">
                            @foreach ($unassignedPermissions as $permission)
                                <div class="permission-mobile-perm">
                                    <div class="permission-mobile-perm-label">
                                        <strong>{{ $permission->label ?? $permission->name }}</strong>
                                        <code class="text-body-secondary">{{ $permission->name }}</code>
                                    </div>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input perm-toggle" type="checkbox"
                                            name="permissions[]" value="{{ $permission->name }}"
                                            data-group-parent="{{ $groupKey }}"
                                            @checked(in_array($permission->name, old('permissions', $rolePermissions))) />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </details>
                @endif
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
    const isMobile = window.matchMedia('(max-width: 767.98px)').matches;
    const activeRoot = document.querySelector(isMobile ? '.permission-mobile-shell' : '.permission-desktop-shell');
    const groupToggles = activeRoot ? activeRoot.querySelectorAll('.group-toggle') : [];
    const permToggles = activeRoot ? activeRoot.querySelectorAll('.perm-toggle') : [];

    if (!selectAll || !activeRoot) {
        return;
    }

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
            activeRoot.querySelectorAll(`[data-group-parent="${group}"]`).forEach(t => {
                t.checked = this.checked;
            });
            updateGlobalSelectAll();
        });
    });

    // Individual Toggle
    permToggles.forEach(t => {
        t.addEventListener('change', function() {
            const group = this.dataset.groupParent;
            const groupToggle = activeRoot.querySelector(`.group-toggle[data-group="${group}"]`);
            const sameGroupPerms = activeRoot.querySelectorAll(`[data-group-parent="${group}"]`);
            
            if (groupToggle) {
                groupToggle.checked = Array.from(sameGroupPerms).every(i => i.checked);
            }
            updateGlobalSelectAll();
        });
    });

    function updateGlobalSelectAll() {
        selectAll.checked = permToggles.length > 0 && Array.from(permToggles).every(t => t.checked);
    }
});
</script>
@endsection
