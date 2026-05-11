@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Logbook')

@section('content')
@include('partials.app-breadcrumb', [
  'items' => [
    ['label' => 'Dashboard', 'url' => route('dashboard.intern')],
    ['label' => 'Logbook', 'url' => route('intern.logbooks.index')],
    ['label' => 'Edit Laporan', 'current' => true],
  ],
])

<div class="row g-6">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header d-flex align-items-center">
        <i class="icon-base ri ri-edit-box-line icon-22px me-2 text-primary"></i>
        <h5 class="card-title mb-0">Edit Logbook Harian</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('intern.logbooks.update', $logbook->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="mb-6">
            <label for="tanggal" class="form-label fw-medium">
              <i class="icon-base ri ri-calendar-event-line icon-20px me-1 text-primary"></i> Tanggal Kegiatan
            </label>
            <input type="date" id="tanggal" name="tanggal"
              class="form-control @error('tanggal') is-invalid @enderror"
              value="{{ old('tanggal', \Carbon\Carbon::parse($logbook->tanggal)->format('Y-m-d')) }}" required />
            @error('tanggal')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-6">
            <label for="uraian_aktivitas" class="form-label fw-medium">
              <i class="icon-base ri ri-file-list-3-line icon-20px me-1 text-primary"></i> Uraian Aktivitas
            </label>
            <textarea id="uraian_aktivitas" name="uraian_aktivitas" rows="5"
              class="form-control @error('uraian_aktivitas') is-invalid @enderror" required>{{ old('uraian_aktivitas', $logbook->uraian_aktivitas) }}</textarea>
            @error('uraian_aktivitas')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-6">
            <label for="pembelajaran_diperoleh" class="form-label fw-medium">
              <i class="icon-base ri ri-lightbulb-line icon-20px me-1 text-primary"></i> Pembelajaran yang Diperoleh
            </label>
            <textarea id="pembelajaran_diperoleh" name="pembelajaran_diperoleh" rows="3"
              class="form-control @error('pembelajaran_diperoleh') is-invalid @enderror" required>{{ old('pembelajaran_diperoleh', $logbook->pembelajaran_diperoleh) }}</textarea>
            @error('pembelajaran_diperoleh')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-6">
            <label for="kendala_dialami" class="form-label fw-medium">
              <i class="icon-base ri ri-error-warning-line icon-20px me-1 text-warning"></i> Kendala yang Dialami
              <span class="text-muted fw-normal">(Opsional)</span>
            </label>
            <textarea id="kendala_dialami" name="kendala_dialami" rows="2"
              class="form-control @error('kendala_dialami') is-invalid @enderror"
              placeholder="Ada kesulitan atau hambatan?">{{ old('kendala_dialami', $logbook->kendala_dialami) }}</textarea>
            @error('kendala_dialami')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex gap-4 pt-2">
            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
              <i class="icon-base ri ri-save-line icon-18px"></i> Simpan Perubahan
            </button>
            <a href="{{ route('intern.logbooks.show', $logbook->id) }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
              <i class="icon-base ri ri-arrow-left-line icon-18px"></i> Batal
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Info Sidebar --}}
  <div class="col-lg-4">
    <div class="card bg-label-warning shadow-none">
      <div class="card-body d-flex flex-column justify-content-between">
        <div class="card-title mb-4">
          <h5 class="text-warning mb-2">Sedang Mengedit Laporan</h5>
          <p class="text-heading">Laporan tanggal:<br /><strong>{{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('l, d F Y') }}</strong></p>
        </div>
        <ul class="list-unstyled mb-0">
          <li class="d-flex align-items-start mb-3">
            <i class="icon-base ri ri-information-line icon-20px me-2 text-warning mt-1 flex-shrink-0"></i>
            <span>Pastikan data yang diubah sudah <strong>akurat</strong>.</span>
          </li>
          <li class="d-flex align-items-start mb-3">
            <i class="icon-base ri ri-information-line icon-20px me-2 text-warning mt-1 flex-shrink-0"></i>
            <span>Perubahan akan langsung <strong>tersimpan</strong> setelah tombol ditekan.</span>
          </li>
        </ul>
        <div class="d-flex justify-content-end h-px-120 mt-4">
          <img class="img-fluid scaleX-n1-rtl" src="{{ asset('assets/img/illustrations/girl-app-academy.png') }}"
            alt="girl illustration" />
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
