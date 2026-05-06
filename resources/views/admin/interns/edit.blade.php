@extends('layouts/contentNavbarLayout')

@section('title', 'Penugasan Mentor Intern')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="py-3 mb-0">
    <span class="text-muted fw-light">Manajemen / Data Intern /</span> Penugasan Mentor
  </h4>
  <a href="{{ route('admin.interns.show', $intern) }}" class="btn btn-outline-secondary">
    <i class="ri-arrow-left-line me-1"></i> Kembali
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-header border-bottom">
        <h5 class="mb-0">Form Penugasan Mentor & Divisi</h5>
      </div>
      <div class="card-body mt-4">
        <div class="d-flex align-items-center mb-4 p-3 bg-label-primary rounded">
          <div class="avatar avatar-sm me-3">
            <span class="avatar-initial rounded-circle bg-primary">{{ strtoupper(substr($intern->name, 0, 2)) }}</span>
          </div>
          <div>
            <h6 class="mb-0">{{ $intern->name }}</h6>
            <small class="text-muted">{{ $intern->institution }} - {{ $intern->major }}</small>
          </div>
        </div>

        <form action="{{ route('admin.interns.update', $intern) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="mb-4">
            <div class="form-floating form-floating-outline">
              <select class="form-select @error('division_id') is-invalid @enderror" id="division_id" name="division_id" required>
                <option value="">Pilih Divisi</option>
                @foreach($divisions as $division)
                <option value="{{ $division->id }}" {{ old('division_id', $intern->division_id) == $division->id ? 'selected' : '' }}>
                  {{ $division->name }}
                </option>
                @endforeach
              </select>
              <label for="division_id">Divisi Penempatan</label>
              @error('division_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <small class="text-muted mt-1 d-block">Menentukan divisi tempat intern bekerja.</small>
          </div>

          <div class="mb-4">
            <div class="form-floating form-floating-outline">
              <select class="form-select @error('mentor_id') is-invalid @enderror" id="mentor_id" name="mentor_id">
                <option value="">Pilih Mentor Pembimbing</option>
                @foreach($mentors as $mentor)
                <option value="{{ $mentor->id }}" {{ old('mentor_id', $intern->mentor_id) == $mentor->id ? 'selected' : '' }}>
                  {{ $mentor->name }} ({{ $mentor->division->name ?? 'Tanpa Divisi' }})
                </option>
                @endforeach
              </select>
              <label for="mentor_id">Mentor Penanggung Jawab</label>
              @error('mentor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <small class="text-muted mt-1 d-block">Mentor ini akan bertanggung jawab penuh memantau logbook dan tugas intern ini.</small>
          </div>

          <div class="mt-5">
            <button type="submit" class="btn btn-primary w-100 py-3 shadow">
              <i class="ri-save-line me-2"></i> Simpan Penugasan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
