@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Divisi')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card shadow-sm border-0">
      <div class="card-header d-flex align-items-center justify-content-between border-bottom py-4">
        <h5 class="m-0 text-primary fw-bold">Edit Data Divisi</h5>
        <a href="{{ route('admin.divisions.index') }}" class="btn btn-sm btn-outline-secondary">
          <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
      </div>
      <div class="card-body py-5">
        <form action="{{ route('admin.divisions.update', $division->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="row g-4">
            {{-- Name --}}
            <div class="col-md-8">
              <div class="form-floating form-floating-outline">
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Divisi Teknologi Informasi" value="{{ old('name', $division->name) }}" required />
                <label for="name">Nama Divisi / Departemen</label>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Code --}}
            <div class="col-md-4">
              <div class="form-floating form-floating-outline">
                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" placeholder="TI" value="{{ old('code', $division->code) }}" required />
                <label for="code">Kode Divisi</label>
                @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Description --}}
            <div class="col-12">
              <div class="form-floating form-floating-outline">
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="Deskripsi singkat divisi..." style="height: 100px">{{ old('description', $division->description) }}</textarea>
                <label for="description">Deskripsi</label>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Is Active --}}
            <div class="col-12">
              <div class="form-check form-switch">
                <input type="hidden" name="is_active" value="0">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $division->is_active) ? 'checked' : '' }}>
                <label class="form-check-input-label" for="is_active">Divisi Aktif</label>
              </div>
            </div>

            <div class="col-12 mt-5">
              <button type="submit" class="btn btn-warning w-100 py-3 shadow">
                <i class="ri-refresh-line me-2"></i> Perbarui Divisi
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
