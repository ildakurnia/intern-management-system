@extends('layouts/contentNavbarLayout')

@section('title', 'Buat Tugas Baru')

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Jobdesk /</span> Buat Tugas Baru
</h4>

<div class="row">
  <div class="col-xxl-8 col-lg-10">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Form Tugas Baru</h5>
        <a href="{{ route('mentor.tasks.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
      </div>
      <div class="card-body">
        <form action="{{ route('mentor.tasks.store') }}" method="POST">
          @csrf
          
          <div class="row mb-3">
            <label class="col-sm-3 col-form-label" for="intern_id">Anak Bimbingan <span class="text-danger">*</span></label>
            <div class="col-sm-9">
              <select name="intern_id" id="intern_id" class="form-select" required>
                <option value="">-- Pilih Anak Bimbingan --</option>
                @foreach($interns as $intern)
                    <option value="{{ $intern->id }}" {{ old('intern_id') == $intern->id ? 'selected' : '' }}>{{ $intern->user->name ?? $intern->name }}</option>
                @endforeach
              </select>
              @error('intern_id') <div class="form-text text-danger">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-3 col-form-label" for="title">Judul Tugas <span class="text-danger">*</span></label>
            <div class="col-sm-9">
              <input type="text" name="title" id="title" class="form-control" placeholder="Masukkan judul tugas" value="{{ old('title') }}" required />
              @error('title') <div class="form-text text-danger">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-3 col-form-label" for="due_date">Tenggat Waktu</label>
            <div class="col-sm-9">
              <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date') }}" />
              @error('due_date') <div class="form-text text-danger">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-3 col-form-label" for="description">Deskripsi Tugas</label>
            <div class="col-sm-9">
              <textarea name="description" id="description" class="form-control" rows="5" placeholder="Jelaskan detail tugas yang harus dikerjakan...">{{ old('description') }}</textarea>
              @error('description') <div class="form-text text-danger">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="row justify-content-end">
            <div class="col-sm-9">
              <button type="submit" class="btn btn-primary">Simpan Tugas</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
