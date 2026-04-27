@extends('layouts/contentNavbarLayout')

@section('title', 'Manajemen Menu Fitur')

@section('content')
<div class="row g-6">
  <div class="col-md-12">
    <div class="card shadow-sm border-0">
      <div class="card-header d-flex align-items-center justify-content-between border-bottom py-4">
        <div class="card-title mb-0">
          <h5 class="m-0 text-primary fw-bold">Daftar Grup Menu Fitur</h5>
          <small class="text-body-secondary">Gunakan untuk mengelompokkan permission sistem agar rapi</small>
        </div>
        <a href="{{ route('menus.create') }}" class="btn btn-primary shadow">
          <i class="ri ri-add-line me-1"></i> Tambah Grup Menu
        </a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive text-nowrap">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th class="py-3">Urutan</th>
                <th class="py-3">Nama Grup Menu</th>
                <th class="py-3">Icon</th>
                <th class="py-3">Total Permission</th>
                <th class="py-3 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse($menus as $menu)
              <tr>
                <td><span class="badge bg-label-secondary">{{ $menu->order }}</span></td>
                <td><span class="fw-bold text-heading">{{ $menu->title }}</span></td>
                <td>
                  @if($menu->icon)
                    <i class="ri {{ $menu->icon }} icon-20px text-primary"></i>
                  @else
                    <span class="text-body-secondary small">No Icon</span>
                  @endif
                </td>
                <td>
                  <span class="badge bg-label-info rounded-pill">{{ $menu->permissions_count ?? $menu->permissions->count() }} Permission</span>
                </td>
                <td class="text-center">
                  <div class="d-flex align-items-center justify-content-center gap-2">
                    <a href="{{ route('menus.edit', $menu->id) }}"
                      class="btn btn-sm btn-icon btn-text-secondary rounded-pill shadow-none"
                      title="Edit">
                      <i class="ri ri-pencil-line text-warning icon-22px"></i>
                    </a>
                    <form action="{{ route('menus.destroy', $menu->id) }}" method="POST"
                      class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus grup menu ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-icon btn-text-secondary rounded-pill shadow-none"
                        title="Hapus">
                        <i class="ri ri-delete-bin-line text-danger icon-22px"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center py-5">
                  <i class="ri ri-layout-grid-line icon-48px text-body-secondary mb-3 d-block"></i>
                  <p class="mb-0 text-body-secondary">Belum ada grup menu</p>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
