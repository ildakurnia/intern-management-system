@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Permission')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="mb-1">Edit Permission</h4>
      <p class="mb-0">Ubah detail permission sistem</p>
    </div>
    <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">
      <i class="icon-base ri ri-arrow-left-line icon-16px me-2"></i> Kembali
    </a>
  </div>

  <div class="card">
    <div class="card-body">
      <form action="{{ route('permissions.update', $permission) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-4 mt-2">
          <div class="col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $permission->name) }}" required>
              <label for="name">Route Permission <span class="text-danger">*</span></label>
              @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" name="guard_name" id="guard_name" class="form-control @error('guard_name') is-invalid @enderror" value="{{ old('guard_name', $permission->guard_name) }}" required>
              <label for="guard_name">Guard name <span class="text-danger">*</span></label>
              @error('guard_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-floating form-floating-outline">
              <select name="menu_id" id="menu_id" class="form-select @error('menu_id') is-invalid @enderror">
                <option value="">Tanpa menu</option>
                @foreach ($menus as $menu)
                  <option value="{{ $menu->id }}" @selected((string) old('menu_id', $permission->menu_id) === (string) $menu->id)>
                    {{ $menu->title }}
                  </option>
                @endforeach
              </select>
              <label for="menu_id">Menu Fitur</label>
              @error('menu_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $permission->sort_order ?? 0) }}" min="0">
              <label for="sort_order">Urutan</label>
              @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="col-12">
            <div class="form-floating form-floating-outline">
              <input type="text" name="label" id="label" class="form-control @error('label') is-invalid @enderror" value="{{ old('label', $permission->label) }}">
              <label for="label">Label UI</label>
              @error('label') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="col-12 mt-5">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary ms-2">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
