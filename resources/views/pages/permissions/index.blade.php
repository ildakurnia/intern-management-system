@extends('layouts/contentNavbarLayout')

@section('title', 'Daftar Permission')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
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
      <div class="d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0">Daftar Permissions</h5>
        <div class="d-flex align-items-center gap-2">
            <input type="text" id="liveSearchPermission" class="form-control custom-search-input" placeholder="Cari permission..." style="width: 280px;">
        </div>
      </div>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
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
            <tr style="background: rgba(var(--bs-primary-rgb), 0.05);">
              <td colspan="5" class="fw-semibold text-primary border-bottom">
                <div class="d-flex align-items-center gap-2">
                  <i class="icon-base ri ri-folder-line icon-20px"></i>
                  {{ $menu->title }}
                  <span class="badge bg-label-primary rounded-pill ms-2">{{ $menu->permissions->count() }}</span>
                </div>
              </td>
            </tr>
            @foreach ($menu->permissions as $permission)
            <tr>
              <td class="ps-6">
                <div class="d-flex align-items-center text-body-secondary">
                  <i class="icon-base ri ri-arrow-right-s-line icon-16px me-2"></i>
                  {{ $menu->title }}
                </div>
              </td>
              <td><code>{{ $permission->name }}</code></td>
              <td>{{ $permission->label ?? $permission->name }}</td>
              <td><span class="badge bg-label-secondary">{{ $permission->guard_name }}</span></td>
              <td class="text-center">
                <div class="d-flex align-items-center justify-content-center gap-1">
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

          {{-- Unassigned Permissions Section --}}
          @if($unassignedPermissions->isNotEmpty())
            <tr style="background: rgba(var(--bs-secondary-rgb), 0.05);">
              <td colspan="5" class="fw-semibold text-secondary border-bottom border-top">
                <div class="d-flex align-items-center gap-2">
                  <i class="icon-base ri ri-question-line icon-20px"></i>
                  Tanpa Menu (Uncategorized)
                  <span class="badge bg-label-secondary rounded-pill ms-2">{{ $unassignedPermissions->count() }}</span>
                </div>
              </td>
            </tr>
            @foreach ($unassignedPermissions as $permission)
            <tr>
              <td class="ps-6">
                <div class="d-flex align-items-center text-body-secondary">
                  <i class="icon-base ri ri-arrow-right-s-line icon-16px me-2"></i>
                  Lainnya
                </div>
              </td>
              <td><code>{{ $permission->name }}</code></td>
              <td>{{ $permission->label ?? $permission->name }}</td>
              <td><span class="badge bg-label-secondary">{{ $permission->guard_name }}</span></td>
              <td class="text-center">
                <div class="d-flex align-items-center justify-content-center gap-1">
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
  </div>

@section('page-style')
<style>
  html body .custom-search-input {
    padding-left: 40px !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23666cff'%3E%3Cpath d='M18.031 16.617l4.283 4.282-1.415 1.415-4.282-4.283A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9 9 4.032 9 9a8.96 8.96 0 0 1-1.969 5.617zm-2.006-.738A7.003 7.003 0 0 0 18 11c0-3.86-3.14-7-7-7s-7 3.14-7 7 3.14 7 7 7a6.967 6.967 0 0 0 4.025-1.282l.008-.007-.008-.032z'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: 12px center !important;
    background-size: 20px !important;
    border-radius: 8px !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    background-color: rgba(255, 255, 255, 0.05) !important;
    color: #fff !important;
    height: 40px !important;
  }
</style>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('liveSearchPermission');
  const tableBody = document.querySelector('table tbody');
  const rows = Array.from(tableBody.querySelectorAll('tr'));

  searchInput.addEventListener('input', function () {
    const query = this.value.toLowerCase().trim();
    let currentMenuHeader = null;
    let menuHasMatch = false;

    rows.forEach((row, index) => {
      // Periksa apakah ini baris Header Menu (Kategori)
      // Header menu memiliki colspan="5" dan background rgba
      const isHeader = row.querySelector('td[colspan="5"]');
      
      if (isHeader) {
        // Simpan header sebelumnya jika ada dan tentukan visibilitasnya
        if (currentMenuHeader) {
          currentMenuHeader.style.display = menuHasMatch ? '' : 'none';
        }
        currentMenuHeader = row;
        menuHasMatch = false; // Reset untuk grup baru
        return;
      }

      // Ini baris data Permission
      const text = row.innerText.toLowerCase();
      if (text.includes(query)) {
        row.style.display = '';
        menuHasMatch = true;
      } else {
        row.style.display = 'none';
      }

      // Jika baris terakhir, proses header terakhir
      if (index === rows.length - 1 && currentMenuHeader) {
        currentMenuHeader.style.display = menuHasMatch ? '' : 'none';
      }
    });

    // Jika pencarian kosong, pastikan semua header tampil
    if (query === '') {
      rows.forEach(r => r.style.display = '');
    }
  });
});
</script>
@endsection
@endsection
