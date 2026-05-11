@extends('layouts/contentNavbarLayout')

@section('title', 'Pengajuan Absensi')

@section('content')
@include('partials.app-breadcrumb', [
  'items' => [
    ['label' => 'Dashboard', 'url' => route('dashboard.intern')],
    ['label' => 'Absensi', 'url' => route('intern.attendances.index')],
    ['label' => 'Pengajuan ' . $typeLabel, 'current' => true],
  ],
])

<div class="row g-6">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Form Pengajuan {{ $typeLabel }}</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('intern.attendances.submissions.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="type" value="{{ $type }}" />

          <div class="mb-6">
            <label for="date" class="form-label">Tanggal</label>
            <input type="date" id="date" name="date" class="form-control @error('date') is-invalid @enderror"
              value="{{ old('date', today()->toDateString()) }}" max="{{ today()->toDateString() }}" required>
            @error('date')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-6">
            <label for="reason" class="form-label">Alasan {{ $typeLabel }}</label>
            <textarea id="reason" name="reason" rows="5" class="form-control @error('reason') is-invalid @enderror"
              placeholder="Jelaskan alasan Anda secara singkat namun jelas." required>{{ old('reason') }}</textarea>
            @error('reason')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-6">
            <label for="attachment" class="form-label">Lampiran (Opsional)</label>
            <input type="file" id="attachment" name="attachment" class="form-control @error('attachment') is-invalid @enderror"
              accept=".jpg,.jpeg,.png,.pdf">
            <div class="form-text">Format yang didukung: JPG, PNG, PDF. Maksimal 2 MB.</div>
            @error('attachment')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex gap-3">
            <button type="submit" class="btn btn-primary">Simpan Pengajuan</button>
            <a href="{{ route('intern.attendances.index') }}" class="btn btn-outline-secondary">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card bg-label-info shadow-none h-100">
      <div class="card-body">
        <h5 class="text-info mb-3">Catatan Pengajuan</h5>
        <ul class="mb-0 ps-4">
          <li class="mb-2">Pengajuan langsung tercatat tanpa approval mentor atau admin.</li>
          <li class="mb-2">Gunakan alasan yang jelas agar mudah dimonitoring.</li>
          <li class="mb-2">Lampiran bisa dipakai untuk mendukung pengajuan sakit atau izin tertentu.</li>
          <li>Pastikan tanggal pengajuan masih berada dalam periode magang Anda.</li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
