@extends('layouts/contentNavbarLayout')

@section('title', 'Buat Logbook Baru')

@section('content')
@include('partials.app-breadcrumb', [
  'items' => [
    ['label' => 'Dashboard', 'url' => route('dashboard.intern')],
    ['label' => 'Logbook', 'url' => route('intern.logbooks.index')],
    ['label' => 'Buat Laporan Baru', 'current' => true],
  ],
])

<div class="row g-6">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header d-flex align-items-center">
        <i class="icon-base ri ri-draft-line icon-22px me-2 text-primary"></i>
        <h5 class="card-title mb-0">Form Logbook Harian</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('intern.logbooks.store') }}" method="POST">
          @csrf

          {{-- Tanggal --}}
          <div class="mb-6">
            <label for="tanggal" class="form-label fw-medium">
              <i class="icon-base ri ri-calendar-event-line icon-20px me-1 text-primary"></i> Tanggal Kegiatan
            </label>
            <input type="date" id="tanggal" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
              value="{{ old('tanggal', date('Y-m-d')) }}" required />
            @error('tanggal')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Uraian Aktivitas --}}
          <div class="mb-6">
            <label for="uraian_aktivitas" class="form-label fw-medium">
              <i class="icon-base ri ri-file-list-3-line icon-20px me-1 text-primary"></i> Uraian Aktivitas
            </label>
            <textarea id="uraian_aktivitas" name="uraian_aktivitas" rows="5"
              class="form-control @error('uraian_aktivitas') is-invalid @enderror"
              placeholder="Apa saja yang Anda kerjakan hari ini?" required>{{ old('uraian_aktivitas') }}</textarea>
            @error('uraian_aktivitas')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Pembelajaran --}}
          <div class="mb-6">
            <label for="pembelajaran_diperoleh" class="form-label fw-medium">
              <i class="icon-base ri ri-lightbulb-line icon-20px me-1 text-primary"></i> Pembelajaran yang Diperoleh
            </label>
            <textarea id="pembelajaran_diperoleh" name="pembelajaran_diperoleh" rows="3"
              class="form-control @error('pembelajaran_diperoleh') is-invalid @enderror"
              placeholder="Skill atau wawasan baru apa yang didapat?" required>{{ old('pembelajaran_diperoleh') }}</textarea>
            @error('pembelajaran_diperoleh')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Kendala --}}
          <div class="mb-6">
            <label for="kendala_dialami" class="form-label fw-medium">
              <i class="icon-base ri ri-error-warning-line icon-20px me-1 text-warning"></i> Kendala yang Dialami
              <span class="text-muted fw-normal">(Opsional)</span>
            </label>
            <textarea id="kendala_dialami" name="kendala_dialami" rows="2"
              class="form-control @error('kendala_dialami') is-invalid @enderror"
              placeholder="Ada kesulitan atau hambatan?">{{ old('kendala_dialami') }}</textarea>
            @error('kendala_dialami')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex gap-4 pt-2">
            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
              <i class="icon-base ri ri-save-line icon-18px"></i> Simpan Laporan
            </button>
            <a href="{{ route('intern.logbooks.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
              <i class="icon-base ri ri-arrow-left-line icon-18px"></i> Batal
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Tips Sidebar --}}
  <div class="col-lg-4">
    <div class="card bg-label-info shadow-none h-100">
      <div class="card-body d-flex flex-column justify-content-between">
        <div class="card-title mb-4">
          <h5 class="text-info mb-2">Tips Mengisi Logbook</h5>
          <p class="text-heading">Laporan yang baik mencerminkan profesionalisme kamu.</p>
        </div>
        <ul class="list-unstyled mb-0">
          <li class="d-flex align-items-start mb-3">
            <i class="icon-base ri ri-check-double-line icon-20px me-2 text-info mt-1 flex-shrink-0"></i>
            <span>Isi uraian aktivitas secara <strong>spesifik</strong> dan <strong>terukur</strong>.</span>
          </li>
          <li class="d-flex align-items-start mb-3">
            <i class="icon-base ri ri-check-double-line icon-20px me-2 text-info mt-1 flex-shrink-0"></i>
            <span>Catat setiap <strong>pembelajaran baru</strong> meski kecil.</span>
          </li>
          <li class="d-flex align-items-start mb-3">
            <i class="icon-base ri ri-check-double-line icon-20px me-2 text-info mt-1 flex-shrink-0"></i>
            <span>Jangan takut melaporkan <strong>kendala</strong> — itu membuktikan kejujuran.</span>
          </li>
          <li class="d-flex align-items-start">
            <i class="icon-base ri ri-check-double-line icon-20px me-2 text-info mt-1 flex-shrink-0"></i>
            <span>Isi <strong>setiap hari kerja</strong> agar rekam jejakmu lengkap.</span>
          </li>
        </ul>
        <div class="d-flex justify-content-end h-px-150 mt-4">
          <img class="img-fluid scaleX-n1-rtl" src="{{ asset('assets/img/illustrations/boy-app-academy.png') }}"
            alt="boy illustration" />
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
