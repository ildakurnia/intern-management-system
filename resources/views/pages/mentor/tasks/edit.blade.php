@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Tugas')

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Jobdesk /</span> Edit Tugas
</h4>

<div class="row">
  <div class="col-xxl-8 col-lg-10">
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Form Edit Tugas</h5>
        <a href="{{ route('mentor.tasks.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
      </div>
      <div class="card-body">
        <form action="{{ route('mentor.tasks.update', $task->id) }}" method="POST">
          @csrf
          @method('PUT')
          
          <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Anak Bimbingan</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" value="{{ $task->intern?->user?->name ?? $task->intern?->name ?? 'Unknown' }}" disabled />
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-3 col-form-label" for="title">Judul Tugas <span class="text-danger">*</span></label>
            <div class="col-sm-9">
              <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $task->title) }}" required />
              @error('title') <div class="form-text text-danger">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-3 col-form-label" for="status">Status Tugas</label>
            <div class="col-sm-9">
              <select name="status" id="status" class="form-select" required>
                  <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>Menunggu</option>
                  <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                  <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Selesai</option>
              </select>
              @error('status') <div class="form-text text-danger">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-3 col-form-label" for="due_date">Tenggat Waktu</label>
            <div class="col-sm-9">
              <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}" />
              @error('due_date') <div class="form-text text-danger">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-3 col-form-label" for="description">Deskripsi Tugas</label>
            <div class="col-sm-9">
              <textarea name="description" id="description" class="form-control" rows="5">{{ old('description', $task->description) }}</textarea>
              @error('description') <div class="form-text text-danger">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="row justify-content-end">
            <div class="col-sm-9">
              <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
