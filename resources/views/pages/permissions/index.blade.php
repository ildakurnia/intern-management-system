@extends('layouts/contentNavbarLayout')

@section('title', 'Daftar Permission')

@section('page-style')
<style>
  .permission-search-shell {
    min-width: 17.5rem;
  }

  .permissions-mobile-shell {
    display: grid;
    gap: 1rem;
  }

  .permissions-mobile-group {
    display: grid;
    gap: 0.75rem;
    border: 1px solid var(--bs-border-color);
    border-radius: 1rem;
    background: var(--bs-card-bg);
    overflow: hidden;
  }

  .permissions-mobile-group-title {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--bs-heading-color);
    font-size: 1rem;
    font-weight: 800;
  }

  .permissions-mobile-group-title .icon-base {
    color: var(--bs-primary);
    font-size: 1rem;
  }

  .permissions-mobile-group-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 1rem;
    cursor: pointer;
    list-style: none;
  }

  .permissions-mobile-group-head::-webkit-details-marker {
    display: none;
  }

  .permissions-mobile-group-left {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    min-width: 0;
  }

  .permissions-mobile-group-icon {
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

  .permissions-mobile-group-count {
    white-space: nowrap;
  }

  .permissions-mobile-group-body {
    border-top: 1px solid var(--bs-border-color);
    padding: 0.25rem 1rem 1rem;
  }

  .permissions-mobile-card {
    border-radius: 1rem;
    background: var(--bs-card-bg);
    overflow: hidden;
  }

  .permissions-mobile-card .card-body { padding: 1rem 0; }

  .permissions-mobile-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.75rem;
  }

  .permissions-mobile-title-wrap {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    min-width: 0;
  }

  .permissions-mobile-title-icon {
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

  .permissions-mobile-title {
    margin: 0;
    color: var(--bs-heading-color);
    font-size: 1rem;
    font-weight: 700;
  }

  .permissions-mobile-subtitle {
    color: var(--bs-secondary-color);
    font-size: 0.875rem;
  }

  .permissions-mobile-meta {
    color: var(--bs-body-color);
    font-size: 0.8rem;
    margin-top: 0.45rem;
  }

  .permissions-mobile-chips { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 1rem; }

  .permissions-mobile-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    border-radius: 999px;
    white-space: nowrap;
  }

  .permissions-mobile-actions { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.5rem; margin-top: 1rem; }

  .permissions-mobile-actions .btn {
    width: 100%;
    border-radius: 0.75rem;
  }

  .permissions-mobile-delete {
    background: var(--bs-danger);
    border-color: var(--bs-danger);
    color: #fff;
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
    <div>
      <h4 class="mb-1">Daftar Permission</h4>
      <p class="mb-0">Setiap kategori (Menu) memiliki permission yang bisa ditetapkan ke role tertentu.</p>
    </div>
    <a href="{{ route('permissions.create') }}" class="btn btn-primary">
      <i class="icon-base ri ri-add-line me-1"></i> Tambah Permission
    </a>
  </div>

  <div class="card">
    <div class="card-header border-bottom">
      <div class="d-flex align-items-center justify-content-between gap-3 ims-mobile-toolbar">
        <h5 class="card-title mb-0">Daftar Permissions</h5>
        <div class="input-group input-group-merge permission-search-shell">
          <span class="input-group-text"><i class="icon-base ri ri-search-line"></i></span>
          <input type="text" id="liveSearchPermission" class="form-control" placeholder="Cari permission...">
        </div>
      </div>
    </div>
    <div class="table-responsive ims-card-table-wrap d-none d-md-block">
      <table class="table table-hover ims-card-table">
        <thead>
          <tr>
            <th>Menu / Fitur</th>
            <th>Nama</th>
            <th>Label</th>
            <th>Guard</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($permissionMenus as $menu)
            <tr class="ims-table-group-row" style="background: rgba(var(--bs-primary-rgb), 0.05);">
              <td colspan="5" class="fw-semibold text-primary border-bottom ims-table-group-cell">
                <div class="d-flex align-items-center gap-2">
                  <i class="icon-base ri ri-folder-line icon-20px"></i>
                  {{ $menu->title }}
                  <span class="badge bg-label-primary rounded-pill ms-2">{{ $menu->permissions->count() }}</span>
                </div>
              </td>
            </tr>
            @foreach ($menu->permissions as $permission)
            <tr>
              <td data-label="Menu / Fitur" class="ps-6">
                <div class="d-flex align-items-center text-body-secondary">
                  <i class="icon-base ri ri-arrow-right-s-line icon-16px me-2"></i>
                  {{ $menu->title }}
                </div>
              </td>
              <td data-label="Nama" class="ims-card-primary"><code>{{ $permission->name }}</code></td>
              <td data-label="Label">{{ $permission->label ?? $permission->name }}</td>
              <td data-label="Guard"><span class="badge bg-label-secondary">{{ $permission->guard_name }}</span></td>
              <td data-label="Aksi" class="text-center ims-card-actions">
                <div class="ims-table-inline-actions">
                  <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-icon btn-text-secondary rounded-pill btn-sm" title="Edit">
                    <i class="icon-base ri ri-edit-box-line icon-20px"></i>
                  </a>
                  <form action="{{ route('permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('Hapus permission ini?');" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-icon btn-text-secondary rounded-pill btn-sm" title="Hapus">
                      <i class="icon-base ri ri-delete-bin-line text-danger icon-20px"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
          @empty
            @if($unassignedPermissions->isEmpty())
            <tr>
              <td colspan="5" class="text-center py-5 text-muted">Belum ada permission tersedia.</td>
            </tr>
            @endif
          @endforelse

          @if($unassignedPermissions->isNotEmpty())
            <tr class="ims-table-group-row" style="background: rgba(var(--bs-secondary-rgb), 0.05);">
              <td colspan="5" class="fw-semibold text-secondary border-bottom border-top ims-table-group-cell">
                <div class="d-flex align-items-center gap-2">
                  <i class="icon-base ri ri-question-line icon-20px"></i>
                  Tanpa Menu (Uncategorized)
                  <span class="badge bg-label-secondary rounded-pill ms-2">{{ $unassignedPermissions->count() }}</span>
                </div>
              </td>
            </tr>
            @foreach ($unassignedPermissions as $permission)
            <tr>
              <td data-label="Menu / Fitur" class="ps-6">
                <div class="d-flex align-items-center text-body-secondary">
                  <i class="icon-base ri ri-arrow-right-s-line icon-16px me-2"></i>
                  Lainnya
                </div>
              </td>
              <td data-label="Nama" class="ims-card-primary"><code>{{ $permission->name }}</code></td>
              <td data-label="Label">{{ $permission->label ?? $permission->name }}</td>
              <td data-label="Guard"><span class="badge bg-label-secondary">{{ $permission->guard_name }}</span></td>
              <td data-label="Aksi" class="text-center ims-card-actions">
                <div class="ims-table-inline-actions">
                  <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-icon btn-text-secondary rounded-pill btn-sm" title="Edit">
                    <i class="icon-base ri ri-edit-box-line icon-20px"></i>
                  </a>
                  <form action="{{ route('permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('Hapus permission ini?');" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-icon btn-text-secondary rounded-pill btn-sm" title="Hapus">
                      <i class="icon-base ri ri-delete-bin-line text-danger icon-20px"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>

    <div class="d-md-none p-3">
      <div class="permissions-mobile-shell">
        @forelse ($permissionMenus as $menu)
          <details class="permissions-mobile-group" data-permission-group @if($loop->first) open @endif>
            <summary class="permissions-mobile-group-head">
              <div class="permissions-mobile-group-left">
                <span class="permissions-mobile-group-icon">
                  <i class="icon-base ri ri-folder-line"></i>
                </span>
                <span class="permissions-mobile-group-title text-truncate">{{ $menu->title }}</span>
              </div>
              <span class="badge bg-label-primary rounded-pill permissions-mobile-group-count">{{ $menu->permissions->count() }}</span>
            </summary>

            <div class="permissions-mobile-group-body">
              @foreach ($menu->permissions as $permission)
                <div class="permissions-mobile-card" data-permission-card>
                  <div class="card-body">
                    <div class="permissions-mobile-head">
                      <div class="min-w-0">
                        <div class="permissions-mobile-title-wrap">
                          <span class="permissions-mobile-title-icon">
                            <i class="icon-base ri ri-shield-user-line"></i>
                          </span>
                          <h6 class="permissions-mobile-title text-truncate">{{ $permission->label ?? $permission->name }}</h6>
                        </div>
                        <div class="permissions-mobile-subtitle mt-1 text-truncate">{{ $permission->name }}</div>
                        <div class="permissions-mobile-meta">
                          Menu: <strong>{{ $menu->title }}</strong>
                        </div>
                      </div>
                      <span class="badge bg-label-secondary rounded-pill">{{ $permission->guard_name }}</span>
                    </div>

                    <div class="permissions-mobile-chips">
                      <span class="badge bg-label-primary rounded-pill permissions-mobile-chip">
                        <i class="icon-base ri ri-price-tag-3-line"></i>
                        <span>{{ $permission->label ?? $permission->name }}</span>
                      </span>
                      <span class="badge bg-label-info rounded-pill permissions-mobile-chip">
                        <i class="icon-base ri ri-shield-line"></i>
                        <span>{{ $permission->guard_name }}</span>
                      </span>
                    </div>

                    <div class="permissions-mobile-actions">
                      <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center gap-1">
                        <i class="icon-base ri ri-pencil-line"></i>
                        <span>Edit</span>
                      </a>
                      <form action="{{ route('permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('Hapus permission ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn permissions-mobile-delete d-inline-flex align-items-center justify-content-center gap-1">
                          <i class="icon-base ri ri-delete-bin-line"></i>
                          <span>Hapus</span>
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </details>
        @empty
          @if($unassignedPermissions->isEmpty())
            <div class="permissions-mobile-card text-center p-4">
              <div class="card-body">
                <i class="icon-base ri ri-shield-keyhole-line icon-32px text-muted mb-2 d-block"></i>
                <p class="mb-0 text-body-secondary">Belum ada permission tersedia.</p>
              </div>
            </div>
          @endif
        @endforelse

        @if($unassignedPermissions->isNotEmpty())
          <details class="permissions-mobile-group" data-permission-group open>
            <summary class="permissions-mobile-group-head">
              <div class="permissions-mobile-group-left">
                <span class="permissions-mobile-group-icon">
                  <i class="icon-base ri ri-question-line"></i>
                </span>
                <span class="permissions-mobile-group-title text-truncate">Tanpa Menu (Uncategorized)</span>
              </div>
              <span class="badge bg-label-secondary rounded-pill permissions-mobile-group-count">{{ $unassignedPermissions->count() }}</span>
            </summary>

            <div class="permissions-mobile-group-body">
              @foreach ($unassignedPermissions as $permission)
                <div class="permissions-mobile-card" data-permission-card>
                  <div class="card-body">
                    <div class="permissions-mobile-head">
                      <div class="min-w-0">
                        <div class="permissions-mobile-title-wrap">
                          <span class="permissions-mobile-title-icon">
                            <i class="icon-base ri ri-shield-user-line"></i>
                          </span>
                          <h6 class="permissions-mobile-title text-truncate">{{ $permission->label ?? $permission->name }}</h6>
                        </div>
                        <div class="permissions-mobile-subtitle mt-1 text-truncate">{{ $permission->name }}</div>
                        <div class="permissions-mobile-meta">
                          Menu: <strong>Lainnya</strong>
                        </div>
                      </div>
                      <span class="badge bg-label-secondary rounded-pill">{{ $permission->guard_name }}</span>
                    </div>

                    <div class="permissions-mobile-chips">
                      <span class="badge bg-label-primary rounded-pill permissions-mobile-chip">
                        <i class="icon-base ri ri-price-tag-3-line"></i>
                        <span>{{ $permission->label ?? $permission->name }}</span>
                      </span>
                      <span class="badge bg-label-info rounded-pill permissions-mobile-chip">
                        <i class="icon-base ri ri-shield-line"></i>
                        <span>{{ $permission->guard_name }}</span>
                      </span>
                    </div>

                    <div class="permissions-mobile-actions">
                      <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center gap-1">
                        <i class="icon-base ri ri-pencil-line"></i>
                        <span>Edit</span>
                      </a>
                      <form action="{{ route('permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('Hapus permission ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn permissions-mobile-delete d-inline-flex align-items-center justify-content-center gap-1">
                          <i class="icon-base ri ri-delete-bin-line"></i>
                          <span>Hapus</span>
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </details>
        @endif
      </div>
    </div>
  </div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('liveSearchPermission');
  const tableBody = document.querySelector('.ims-card-table tbody');
  const mobileGroups = Array.from(document.querySelectorAll('[data-permission-group]'));

  if (!searchInput || !tableBody) {
    return;
  }

  const rows = Array.from(tableBody.querySelectorAll('tr'));

  searchInput.addEventListener('input', function () {
    const query = this.value.toLowerCase().trim();
    let currentMenuHeader = null;
    let menuHasMatch = false;

    if (query === '') {
      rows.forEach(row => row.style.display = '');
      mobileGroups.forEach(group => {
        group.style.display = '';
        group.querySelectorAll('[data-permission-card]').forEach(card => {
          card.style.display = '';
        });
      });
      return;
    }

    rows.forEach((row, index) => {
      const isHeader = row.classList.contains('ims-table-group-row');

      if (isHeader) {
        if (currentMenuHeader) {
          currentMenuHeader.style.display = menuHasMatch ? '' : 'none';
        }

        currentMenuHeader = row;
        menuHasMatch = false;
        return;
      }

      const isMatch = row.innerText.toLowerCase().includes(query);
      row.style.display = isMatch ? '' : 'none';
      menuHasMatch = menuHasMatch || isMatch;

      if (index === rows.length - 1 && currentMenuHeader) {
        currentMenuHeader.style.display = menuHasMatch ? '' : 'none';
      }
    });

    mobileGroups.forEach(group => {
      let visibleCount = 0;
      group.querySelectorAll('[data-permission-card]').forEach(card => {
        const isMatch = card.textContent.toLowerCase().includes(query);
        card.style.display = isMatch ? '' : 'none';
        visibleCount += isMatch ? 1 : 0;
      });

      group.style.display = visibleCount > 0 ? '' : 'none';
      group.open = visibleCount > 0;
    });
  });
});
</script>
@endsection
