@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Tugas')

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Jobdesk /</span> Detail Tugas
</h4>

@if (session('status'))
<div class="alert alert-success alert-dismissible" role="alert">
  {{ session('status') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Informasi Tugas</h5>
        <a href="{{ route('intern.tasks.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
      </div>
      <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6 mb-3 mb-md-0">
                <small class="text-muted text-uppercase">Pemberi Tugas</small>
                <h6 class="mb-0 fs-6">{{ $task->mentor->name ?? '-' }}</h6>
            </div>
            <div class="col-md-6">
                <small class="text-muted text-uppercase">Status</small>
                <div class="mt-1">
                    @if($task->status === 'completed')
                        <span class="badge bg-label-success">Selesai</span>
                    @elseif($task->status === 'in_progress')
                        <span class="badge bg-label-warning">Sedang Dikerjakan</span>
                    @else
                        <span class="badge bg-label-secondary">Menunggu</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 mb-3 mb-md-0">
                <small class="text-muted text-uppercase">Judul Tugas</small>
                <h6 class="mb-0 fs-6">{{ $task->title }}</h6>
            </div>
            <div class="col-md-6">
                <small class="text-muted text-uppercase">Tenggat Waktu</small>
                <h6 class="mb-0 fs-6">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->translatedFormat('d F Y') : 'Tidak ada tenggat' }}</h6>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <small class="text-muted text-uppercase">Deskripsi Tugas</small>
                <div class="p-3 bg-lighter rounded mt-2" style="min-height: 100px;">
                    {!! nl2br(e($task->description)) ?: '<em class="text-muted">Tidak ada deskripsi.</em>' !!}
                </div>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top">
            <h6 class="mb-3">Perbarui Status Tugas</h6>
            <form action="{{ route('intern.tasks.update-status', $task->id) }}" method="POST" class="d-flex align-items-center gap-3">
                @csrf
                @method('PUT')
                <div class="w-px-200">
                    <select name="status" class="form-select">
                        <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                        <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Status</button>
            </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
