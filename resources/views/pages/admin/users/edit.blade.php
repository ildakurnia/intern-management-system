@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Pengguna')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card shadow-sm border-0">
      <div class="card-header d-flex align-items-center justify-content-between border-bottom py-4">
        <h5 class="m-0 text-primary fw-bold">Edit Data Pengguna</h5>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
          <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
      </div>
      <div class="card-body py-5">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="row g-4">
            {{-- Name --}}
            <div class="col-12">
              <div class="form-floating form-floating-outline">
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="John Doe" value="{{ old('name', $user->name) }}" required />
                <label for="name">Nama Lengkap</label>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Email --}}
            <div class="col-12">
              <div class="form-floating form-floating-outline">
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="john@example.com" value="{{ old('email', $user->email) }}" required />
                <label for="email">Alamat Email</label>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Role --}}
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                  <option value="">Pilih Role</option>
                  @foreach($roles as $role)
                  <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                    {{ ucfirst($role->name) }}
                  </option>
                  @endforeach
                </select>
                <label for="role">Hak Akses (Role)</label>
                @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Division --}}
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <select class="form-select @error('division_id') is-invalid @enderror" id="division_id" name="division_id">
                  <option value="">Pilih Divisi (Opsional)</option>
                  @foreach($divisions as $division)
                  <option value="{{ $division->id }}" {{ old('division_id', $user->division_id) == $division->id ? 'selected' : '' }}>
                    {{ $division->name }}
                  </option>
                  @endforeach
                </select>
                <label for="division_id">Divisi / Departemen</label>
                @error('division_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-12">
              <hr class="my-3">
              <p class="text-body-secondary small mb-3">Kosongkan password jika tidak ingin mengubahnya.</p>
            </div>

            {{-- Password --}}
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="············" />
                <label for="password">Password Baru</label>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Password Confirmation --}}
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="············" />
                <label for="password_confirmation">Konfirmasi Password Baru</label>
              </div>
            </div>

            <div class="col-12 mt-5">
              <button type="submit" class="btn btn-warning w-100 py-3 shadow">
                <i class="ri-refresh-line me-2"></i> Perbarui Data Pengguna
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
