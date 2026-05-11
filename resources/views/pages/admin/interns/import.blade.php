@extends('layouts/contentNavbarLayout')

@section('title', 'Import Data Intern')

@section('page-style')
  <style>
    .intern-import-page {
      display: grid;
      gap: 1.35rem;
    }

    .intern-import-card {
      border: 1px solid rgba(148, 163, 184, 0.14);
      border-radius: 1.6rem;
      background: rgba(255, 255, 255, 0.97);
      box-shadow: 0 18px 44px rgba(15, 23, 42, 0.06);
    }

    .intern-import-hero {
      padding: 1.8rem;
    }

    .intern-import-eyebrow {
      margin: 0 0 0.55rem;
      color: #4f46e5;
      font-size: 0.82rem;
      font-weight: 800;
      letter-spacing: 0.12em;
      text-transform: uppercase;
    }

    .intern-import-title {
      margin: 0;
      color: #1f2a44;
      font-size: clamp(1.9rem, 3vw, 2.5rem);
      font-weight: 800;
      letter-spacing: -0.04em;
    }

    .intern-import-subtitle {
      max-width: 42rem;
      margin: 0.75rem 0 0;
      color: #475569;
      font-size: 1rem;
      line-height: 1.7;
    }

    .intern-import-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.85rem;
      margin-top: 1.35rem;
    }

    .intern-import-btn-primary,
    .intern-import-btn-secondary {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.55rem;
      min-height: 3rem;
      padding: 0.85rem 1.25rem;
      border-radius: 1rem;
      font-weight: 700;
      text-decoration: none;
    }

    .intern-import-btn-primary {
      background: linear-gradient(135deg, #5b5cf0, #7468ff);
      color: #fff;
      box-shadow: 0 16px 28px rgba(91, 92, 240, 0.22);
    }

    .intern-import-btn-secondary {
      border: 1px solid rgba(148, 163, 184, 0.24);
      color: #334155;
      background: #fff;
    }

    .intern-import-grid {
      display: grid;
      grid-template-columns: minmax(0, 1.35fr) minmax(18rem, 0.92fr);
      gap: 1.2rem;
    }

    .intern-import-panel {
      padding: 1.5rem;
    }

    .intern-import-panel h2 {
      margin: 0 0 1rem;
      color: #1f2a44;
      font-size: 1.6rem;
      font-weight: 800;
      letter-spacing: -0.03em;
    }

    .intern-import-list {
      display: grid;
      gap: 0.8rem;
      margin: 0;
      padding: 0;
      list-style: none;
    }

    .intern-import-list li {
      display: flex;
      align-items: center;
      gap: 0.7rem;
      color: #64748b;
      font-size: 1rem;
      font-weight: 600;
    }

    .intern-import-list li i {
      color: #4f46e5;
    }

    .intern-import-form {
      display: grid;
      gap: 1rem;
    }

    .intern-import-form label {
      color: #334155;
      font-size: 0.95rem;
      font-weight: 700;
    }

    .intern-import-form input[type='file'] {
      width: 100%;
      padding: 0.9rem 1rem;
      border: 1px solid rgba(148, 163, 184, 0.24);
      border-radius: 1rem;
      background: #fff;
      color: #475569;
    }

    .intern-import-submit {
      min-height: 3.1rem;
      border: 0;
      border-radius: 1rem;
      background: linear-gradient(135deg, #5b5cf0, #7468ff);
      color: #fff;
      font-weight: 800;
      box-shadow: 0 16px 28px rgba(91, 92, 240, 0.2);
    }

    @media (max-width: 991.98px) {
      .intern-import-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
@endsection

@section('content')
  <div class="intern-import-page">
    @include('partials.app-breadcrumb', [
      'items' => [
        ['label' => 'Dashboard', 'url' => route('dashboard.admin')],
        ['label' => 'Data Intern', 'url' => route('admin.interns.index')],
        ['label' => 'Import Data', 'current' => true],
      ],
    ])

    <section class="intern-import-card intern-import-hero">
      <p class="intern-import-eyebrow">Admin Area</p>
      <h1 class="intern-import-title">Import Data Intern</h1>
      <p class="intern-import-subtitle">Upload data awal intern agar mereka bisa registrasi memakai email dan NIM/NIS. Halaman ini sekarang tetap berada dalam alur Data Intern, jadi sidebar akan menyesuaikan.</p>

      <div class="intern-import-actions">
        <a href="{{ route('admin.interns.template') }}" class="intern-import-btn-primary">
          <i class="ri ri-download-2-line"></i>
          Download Template
        </a>
        <a href="{{ route('admin.interns.index') }}" class="intern-import-btn-secondary">
          <i class="ri ri-group-line"></i>
          Daftar Intern
        </a>
      </div>
    </section>

    <section class="intern-import-grid">
      <article class="intern-import-card intern-import-panel">
        <h2>Kolom Excel</h2>
        <ul class="intern-import-list">
          <li><i class="ri ri-check-line"></i>nama</li>
          <li><i class="ri ri-check-line"></i>email</li>
          <li><i class="ri ri-check-line"></i>nim_nis</li>
          <li><i class="ri ri-check-line"></i>tipe_peserta</li>
          <li><i class="ri ri-check-line"></i>divisi</li>
          <li><i class="ri ri-check-line"></i>tanggal_mulai_magang</li>
          <li><i class="ri ri-check-line"></i>tanggal_selesai_magang</li>
        </ul>
      </article>

      <article class="intern-import-card intern-import-panel">
        <h2>Upload File</h2>
        <form action="{{ route('admin.interns.import.store') }}" method="POST" enctype="multipart/form-data" class="intern-import-form">
          @csrf

          <div>
            <label for="file">File Excel / CSV</label>
            <input id="file" type="file" name="file" accept=".xlsx,.csv" required>
            @error('file')
              <small class="text-danger d-block mt-2">{{ $message }}</small>
            @enderror
          </div>

          <button type="submit" class="intern-import-submit">Import Data</button>
        </form>
      </article>
    </section>
  </div>
@endsection
