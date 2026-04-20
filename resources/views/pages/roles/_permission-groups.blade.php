@php
    $selectedPermissions = collect(old('permissions', $rolePermissions ?? []));
@endphp

<section class="settings-access-panel">
    <div class="settings-access-toolbar">
        <div>
            <span>Show</span>
            <select aria-label="Show entries">
                <option selected>10</option>
                <option>25</option>
                <option>50</option>
            </select>
            <span>entries</span>
        </div>

        <div class="settings-access-search">
            <label for="permission-search">Search:</label>
            <input id="permission-search" type="search" placeholder="Search menu..." data-permission-search>
        </div>
    </div>

    <div class="settings-access-list" data-permission-list>
        <div class="settings-access-label">Menu access</div>

        <div class="settings-access-row settings-access-row-master">
            <div></div>
            <label class="permission-master">
                <input type="checkbox" data-permission-master>
                <span class="toggle-control"></span>
                <span>All</span>
            </label>
        </div>

        @foreach ($permissionMenus as $menu)
            <div class="settings-access-group" data-permission-feature>
                <div class="settings-access-row settings-access-menu-row">
                    <div>
                        <span>{{ $menu->title }}</span>
                    </div>
                    <label class="permission-switch">
                        <input type="checkbox" data-permission-group-toggle @disabled($menu->permissions->isEmpty())>
                        <span class="toggle-control"></span>
                        <span>Access</span>
                    </label>
                </div>

                @foreach ($menu->permissions as $permission)
                    <label class="settings-access-row settings-access-permission-row">
                        <span class="settings-access-child-name">
                            <span class="settings-access-caret">&rsaquo;</span>
                            <span>{{ $permission->label ?? $permission->name }}</span>
                        </span>
                        <span class="permission-switch">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $permission->name }}"
                                data-permission-checkbox
                                @checked($selectedPermissions->contains($permission->name))
                            >
                            <span class="toggle-control"></span>
                            <span>Access</span>
                        </span>
                    </label>
                @endforeach

                @if ($menu->permissions->isEmpty())
                    <div class="settings-access-row settings-access-permission-row">
                        <span class="settings-access-child-name">
                            <span class="settings-access-caret">&rsaquo;</span>
                            <span>Belum ada route permission</span>
                        </span>
                        <span class="muted-text">-</span>
                    </div>
                @endif
            </div>
        @endforeach

        @if ($permissionMenus->isEmpty())
            <div class="settings-access-row">
                <span>Belum ada menu</span>
                <span class="muted-text">Jalankan migration dan seeder setelah disetujui.</span>
            </div>
        @endif
    </div>
</section>
