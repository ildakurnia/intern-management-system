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
        <a href="{{ route('mentor.tasks.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
      </div>
      <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6 mb-3 mb-md-0">
                <small class="text-muted text-uppercase">Anak Bimbingan</small>
                <h6 class="mb-0 fs-6">{{ $task->intern->user->name ?? $task->intern->name }}</h6>
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

        <div class=\"mt-4 pt-3 border-top d-flex gap-2\">
            <a href=\"{{ route('mentor.tasks.edit', \$task->id) }}\" class=\"btn btn-primary\"><i class=\"ri-pencil-line me-1\"></i> Edit Tugas</a>
            <form action=\"{{ route('mentor.tasks.destroy', \$task->id) }}\" method=\"POST\" onsubmit=\"return confirm('Apakah Anda yakin ingin menghapus tugas ini?');\">
                @csrf
                @method('DELETE')
                <button type=\"submit\" class=\"btn btn-outline-danger\"><i class=\"ri-delete-bin-line me-1\"></i> Hapus</button>
            </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
