@extends('layouts/contentNavbarLayout')

@section('title', 'Upload Berkas')
@section('hide_topbar_context', 'true')

@php
  $documentFields = [
      [
          'name' => 'ktp',
          'label' => 'KTP',
          'path' => $intern->ktp_path,
      ],
      [
          'name' => 'student_card',
          'label' => 'Kartu Siswa/Mahasiswa',
          'path' => $intern->student_card_path,
      ],
      [
          'name' => 'bpjs',
          'label' => 'BPJS Ketenagakerjaan',
          'path' => $intern->bpjs_path,
      ],
      [
          'name' => 'recommendation_letter',
          'label' => 'Surat Pengantar',
          'path' => $intern->recommendation_letter_path,
      ],
  ];
@endphp

@section('page-style')
  <style>
    .content-wrapper > .container-xxl.container-p-y,
    .content-wrapper > .container-fluid.container-p-y {
      padding-top: 0.55rem !important;
    }

    .intern-documents-heading {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 1rem;
    }

    .intern-documents-title-wrap {
      min-width: 0;
    }

    .intern-documents-title-row {
      display: flex;
      align-items: center;
      gap: 0.62rem;
      margin-bottom: 0.22rem;
    }

    .intern-documents-title-icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 2.2rem;
      height: 2.2rem;
      border-radius: 0.8rem;
      background: rgba(var(--bs-primary-rgb), 0.1);
      color: var(--bs-primary);
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45);
      flex-shrink: 0;
    }

    .intern-documents-title-icon i {
      font-size: 1.04rem;
      line-height: 1;
    }

    .intern-documents-title {
      margin: 0;
      color: #172036;
      font-size: clamp(1.46rem, 1.6vw, 1.8rem);
      font-weight: 800;
      line-height: 1.04;
      letter-spacing: -0.03em;
    }

    .intern-documents-subtitle {
      margin: 0;
      color: #6b7280;
      font-size: 0.88rem;
      line-height: 1.45;
      max-width: 34rem;
    }

    .intern-documents-status {
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      padding: 0.52rem 0.8rem;
      border-radius: 999px;
      background: rgba(var(--bs-primary-rgb), 0.08);
      color: var(--bs-primary);
      font-size: 0.76rem;
      font-weight: 700;
      white-space: nowrap;
      flex-shrink: 0;
    }

    .intern-documents-status i {
      font-size: 0.95rem;
    }

    .intern-document-item {
      border: 1px solid var(--bs-border-color);
      border-radius: 1.5rem;
      padding: 1.5rem;
      background: var(--bs-card-bg);
      box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
      color: var(--bs-body-color);
    }

    .intern-document-panel {
      border: 1px solid var(--bs-border-color);
      border-radius: 1.65rem;
      background: var(--bs-card-bg);
      box-shadow: 0 14px 32px rgba(15, 23, 42, 0.05);
      overflow: hidden;
      color: var(--bs-body-color);
    }

    .intern-document-panel .card-body {
      padding: 1.75rem;
    }

    .intern-document-panel-header {
      padding-bottom: 1.5rem;
      margin-bottom: 1.5rem;
      border-bottom: 1px solid var(--bs-border-color);
    }

    .intern-document-list .form-control {
      min-height: 3.25rem;
    }

    .intern-document-list {
      display: grid;
      gap: 1rem;
    }

    .intern-document-row + .intern-document-row {
      margin-top: 1.5rem;
      padding-top: 1.5rem;
      border-top: 1px solid var(--bs-border-color);
    }

    .intern-documents-title {
      color: var(--bs-heading-color);
    }

    .intern-documents-subtitle,
    .intern-document-panel p.text-body-secondary {
      color: var(--bs-secondary-color) !important;
    }

    .intern-document-row h5,
    .intern-document-row .form-label {
      color: var(--bs-heading-color);
    }

    .intern-document-panel .form-control {
      background-color: var(--bs-body-bg);
      border-color: var(--bs-border-color);
      color: var(--bs-body-color);
    }

    .intern-document-panel .form-control::file-selector-button {
      background-color: var(--bs-tertiary-bg);
      color: var(--bs-body-color);
      border: 0;
      padding: 0.55rem 0.9rem;
      margin-inline-end: 0.9rem;
      border-radius: 0.75rem;
    }

    .intern-document-panel .form-control:hover::file-selector-button {
      filter: brightness(0.98);
    }

    .intern-document-panel .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.12);
    }

    .intern-document-view-btn {
      border: 0;
      color: #fff;
      box-shadow: none;
      background: linear-gradient(180deg, #4f46e5 0%, #2563eb 100%);
    }

    .intern-document-view-btn:hover,
    .intern-document-view-btn:focus {
      color: #fff;
      box-shadow: none;
      filter: brightness(1.02);
    }

    html[data-bs-theme="dark"] .intern-document-view-btn {
      background: linear-gradient(180deg, #60a5fa 0%, #3b82f6 100%);
    }

    html[data-bs-theme="dark"] .intern-document-item,
    html[data-bs-theme="dark"] .intern-document-panel {
      border-color: rgba(148, 163, 184, 0.18);
      background: rgba(17, 24, 39, 0.88);
      box-shadow: 0 14px 32px rgba(0, 0, 0, 0.24);
    }

    html[data-bs-theme="dark"] .intern-documents-title,
    html[data-bs-theme="dark"] .intern-document-row h5,
    html[data-bs-theme="dark"] .intern-document-row .form-label {
      color: #f8fafc;
    }

    html[data-bs-theme="dark"] .intern-documents-subtitle,
    html[data-bs-theme="dark"] .intern-document-panel p.text-body-secondary {
      color: #cbd5e1 !important;
    }

    html[data-bs-theme="dark"] .intern-document-panel-header,
    html[data-bs-theme="dark"] .intern-document-row + .intern-document-row {
      border-color: rgba(148, 163, 184, 0.16);
    }

    html[data-bs-theme="dark"] .intern-document-panel .form-control {
      background-color: rgba(15, 23, 42, 0.72);
      border-color: rgba(148, 163, 184, 0.22);
      color: #e5e7eb;
    }

    html[data-bs-theme="dark"] .intern-document-panel .form-control::file-selector-button {
      background-color: rgba(255, 255, 255, 0.08);
      color: #e5e7eb;
    }

    @media (max-width: 767.98px) {
      .intern-documents-heading {
        flex-direction: column;
        align-items: flex-start;
      }

      .intern-documents-title-row {
        gap: 0.58rem;
      }

      .intern-documents-title-icon {
        width: 2.1rem;
        height: 2.1rem;
        border-radius: 0.75rem;
      }

      .intern-documents-subtitle {
        font-size: 0.84rem;
      }

      .intern-document-panel .card-body {
        padding: 1.1rem;
      }

      .intern-document-list {
        gap: 1.15rem;
      }

      .intern-document-panel-header {
        padding-bottom: 1.15rem;
        margin-bottom: 1.15rem;
      }

      .intern-document-row + .intern-document-row {
        margin-top: 1.15rem;
        padding-top: 1.15rem;
      }

      .intern-document-row .row {
        --bs-gutter-x: 1rem;
        --bs-gutter-y: 1rem;
      }

      .intern-document-item {
        padding: 1.2rem;
      }
    }
  </style>
@endsection

@section('content')
  <div class="card intern-document-panel border-0">
    <div class="card-body">
      <div class="intern-document-panel-header">
        <div class="intern-documents-heading">
          <div class="intern-documents-title-wrap">
            <div class="intern-documents-title-row">
              <span class="intern-documents-title-icon" aria-hidden="true">
                <i class="ri ri-file-upload-line"></i>
              </span>
              <h1 class="intern-documents-title">Upload Berkas</h1>
            </div>
            <p class="intern-documents-subtitle">Kelola dan perbarui dokumen administrasi magang Anda.</p>
          </div>

          @if (! $intern->hasCompletedDocuments())
            <div class="intern-documents-status">
              <i class="ri ri-sparkling-line"></i>
              <span>Langkah 2 dari 2</span>
            </div>
          @endif
        </div>
      </div>

      <form action="{{ route('intern.documents.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="intern-document-list">
          @foreach ($documentFields as $field)
    @php
      $hasDocument = filled($field['path']);
      $documentUrl = $hasDocument ? route('intern.documents.preview', ['field' => $field['name']]) : null;
    @endphp

            <div class="intern-document-row">
              <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                  <div class="d-flex flex-column gap-2">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                      <h5 class="mb-0">{{ $field['label'] }}</h5>
                      @if ($hasDocument)
                        <span class="badge bg-label-success rounded-pill">Sudah upload</span>
                      @else
                        <span class="badge bg-label-warning rounded-pill">Belum upload</span>
                      @endif
                    </div>

                    <p class="text-body-secondary mb-0">
                      {{ $hasDocument
                          ? 'File sudah tersedia. Upload file baru jika ingin mengganti dokumen yang lama.'
                          : 'Silakan upload dokumen ini untuk melengkapi persyaratan intern.' }}
                    </p>

                    @if ($documentUrl)
                      <div>
                        <button
                          type="button"
                          class="btn btn-sm intern-document-view-btn"
                          data-document-preview
                          data-document-url="{{ $documentUrl }}"
                          data-document-title="{{ $field['label'] }}">
                          <i class="icon-base ri ri-eye-line me-1"></i> Lihat Dokumen
                        </button>
                      </div>
                    @endif
                  </div>
                </div>

                <div class="col-lg-5">
                  <label for="{{ $field['name'] }}" class="form-label fw-medium">
                    Upload {{ $field['label'] }}
                    @if (! $hasDocument)
                      <span class="text-danger">*</span>
                    @endif
                  </label>
                  <input
                    id="{{ $field['name'] }}"
                    type="file"
                    name="{{ $field['name'] }}"
                    accept=".jpg,.jpeg,.png,.pdf"
                    class="form-control @error($field['name']) is-invalid @enderror"
                    @required(! $hasDocument)>
                  @error($field['name'])
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
            <i class="icon-base ri ri-save-line me-2"></i>
            {{ $intern->hasCompletedDocuments() ? 'Simpan Perubahan' : 'Simpan & Selesaikan Onboarding' }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <div class="modal fade" id="internDocumentPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="internDocumentPreviewTitle">Preview Dokumen</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0" style="height: 80vh; background: #f8f9fa;">
          <iframe id="internDocumentPreviewFrame" src="" style="width: 100%; height: 100%; border: none;" title="Preview Dokumen"></iframe>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('page-script')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const modalEl = document.getElementById('internDocumentPreviewModal');
      const modal = modalEl ? new bootstrap.Modal(modalEl) : null;
      const frameEl = document.getElementById('internDocumentPreviewFrame');
      const titleEl = document.getElementById('internDocumentPreviewTitle');

      document.querySelectorAll('[data-document-preview]').forEach(function (button) {
        button.addEventListener('click', function () {
          const url = button.dataset.documentUrl;
          const title = button.dataset.documentTitle || 'Dokumen';

          if (!url) {
            return;
          }

          if (titleEl) {
            titleEl.textContent = 'Preview Dokumen: ' + title;
          }

          if (frameEl) {
            frameEl.src = url;
          }

          modal?.show();
        });
      });

      modalEl?.addEventListener('hidden.bs.modal', function () {
        if (frameEl) {
          frameEl.src = '';
        }
      });
    });
  </script>
@endsection
