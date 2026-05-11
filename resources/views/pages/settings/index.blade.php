@extends('layouts/layoutMaster')

@section('title', 'Settings')

@section('content')
  <div class="row g-6">
    <div class="col-12">
      <div class="card border-0 shadow-sm" style="border-radius: 1.5rem;">
        <div class="card-body p-5 p-lg-6">
          <div class="d-flex flex-column flex-lg-row justify-content-between gap-4">
            <div>
              <span class="badge rounded-pill text-bg-light mb-3">IMS Settings</span>
              <h3 class="mb-2">Pengaturan Workspace IMS</h3>
              <p class="text-body-secondary mb-0">Kelola preferensi akun, tampilan sistem, dan kebutuhan workspace dari satu halaman yang lebih terpusat.</p>
            </div>
            <div class="d-flex align-items-start">
              <a href="{{ route('dashboard') }}" class="btn btn-primary">Kembali ke Dashboard</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card border-0 shadow-sm h-100" style="border-radius: 1.25rem;">
        <div class="card-body p-5">
          <div class="d-flex align-items-center gap-3 mb-4">
            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-label-primary" style="width:3rem;height:3rem;">
              <i class="ri ri-user-settings-line"></i>
            </span>
            <div>
              <h5 class="mb-1">Akun & Profil</h5>
              <p class="text-body-secondary mb-0">Kelola identitas akun dan preferensi dasar pengguna.</p>
            </div>
          </div>
          <p class="text-body-secondary mb-4">Halaman ini disiapkan sebagai pusat pengaturan akun untuk IMS Portal. Anda bisa melanjutkan dengan menambahkan preferensi profile sesuai kebutuhan perusahaan.</p>
          <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">Buka Ringkasan Akun</a>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card border-0 shadow-sm h-100" style="border-radius: 1.25rem;">
        <div class="card-body p-5">
          <div class="d-flex align-items-center gap-3 mb-4">
            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-label-info" style="width:3rem;height:3rem;">
              <i class="ri ri-layout-grid-line"></i>
            </span>
            <div>
              <h5 class="mb-1">Workspace Preferences</h5>
              <p class="text-body-secondary mb-0">Atur pengalaman penggunaan dashboard agar tetap konsisten.</p>
            </div>
          </div>
          <ul class="list-unstyled text-body-secondary mb-0 d-grid gap-3">
            <li class="d-flex align-items-center gap-2"><i class="ri ri-checkbox-circle-line text-primary"></i>Pengaturan tema dan mode tampilan</li>
            <li class="d-flex align-items-center gap-2"><i class="ri ri-checkbox-circle-line text-primary"></i>Kontrol notifikasi workspace</li>
            <li class="d-flex align-items-center gap-2"><i class="ri ri-checkbox-circle-line text-primary"></i>Preferensi modul yang paling sering digunakan</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
@endsection
