@extends('layouts/contentNavbarLayout')

@section('title', 'Daftar Tugas')

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Jobdesk /</span> Daftar Tugas
</h4>

@if (session('status'))
<div class="alert alert-success alert-dismissible" role="alert">
  {{ session('status') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar Tugas</h5>
    <a href="{{ route('mentor.tasks.create') }}" class="btn btn-primary"><i class="ri-add-line me-1"></i> Buat Tugas Baru</a>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Anak Bimbingan</th>
          <th>Judul Tugas</th>
          <th>Status</th>
          <th>Tenggat Waktu</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse ($tasks as $task)
        <tr>
          <td>
            <div class="d-flex align-items-center">
              <div class="avatar avatar-sm me-3">
                <span class="avatar-initial rounded-circle bg-label-primary">{{ strtoupper(substr($task->intern?->user?->name ?? $task->intern?->name ?? 'U', 0, 2)) }}</span>
              </div>
              <div>
                <h6 class="mb-0 text-truncate">{{ $task->intern?->user?->name ?? $task->intern?->name ?? 'Unknown' }}</h6>
              </div>
            </div>
          </td>
          <td><strong>{{ \Illuminate\Support\Str::limit($task->title, 40) }}</strong></td>
          <td>
            @if($task->status === 'completed')
              <span class="badge bg-label-success me-1">Selesai</span>
            @elseif($task->status === 'in_progress')
              <span class="badge bg-label-warning me-1">Sedang Dikerjakan</span>
            @else
              <span class="badge bg-label-secondary me-1">Menunggu</span>
            @endif
          </td>
          <td>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->translatedFormat('d M Y') : '-' }}</td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                <i class="icon-base ri ri-more-2-line"></i>
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('mentor.tasks.show', $task->id) }}"><i class="ri-eye-line me-1"></i> Detail</a>
                <a class="dropdown-item" href="{{ route('mentor.tasks.edit', $task->id) }}"><i class="ri-pencil-line me-1"></i> Edit</a>
                <form action="{{ route('mentor.tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="dropdown-item text-danger"><i class="ri-delete-bin-line me-1"></i> Hapus</button>
                </form>
              </div>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center py-4 text-muted">Belum ada tugas yang ditemukan.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
