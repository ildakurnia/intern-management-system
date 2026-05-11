@extends('layouts/contentNavbarLayout')

@section('title', 'Profil Saya')

@section('page-style')
<style>
  .account-profile-page {
    display: grid;
    gap: 1rem;
  }

  .account-profile-page .card {
    border: 1px solid rgba(148, 163, 184, 0.14);
    border-radius: 1.5rem;
    box-shadow: 0 16px 42px rgba(15, 23, 42, 0.06);
  }

  .account-profile-hero {
    overflow: hidden;
    border: 0;
    color: #fff;
    background:
      radial-gradient(circle at top right, rgba(255,255,255,0.18), transparent 26%),
      radial-gradient(circle at bottom left, rgba(125, 115, 255, 0.22), transparent 30%),
      linear-gradient(135deg, #2f27c7 0%, #4f46e5 52%, #625cf2 100%);
  }

  .account-profile-badge {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    padding: .5rem .85rem;
    border-radius: 999px;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.12);
    font-weight: 700;
  }

  .account-profile-avatar {
    width: 4.25rem;
    height: 4.25rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 1.35rem;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.12);
    font-size: 1.6rem;
    font-weight: 800;
  }

  .account-profile-meta {
    color: rgba(255,255,255,.72);
    font-size: .95rem;
  }

  .account-profile-form-card {
    padding: 1.5rem;
  }

  .account-profile-section-title {
    margin-bottom: .2rem;
    color: #1f2937;
    font-size: 1.1rem;
    font-weight: 800;
  }

  .account-profile-section-note {
    color: #8d93ac;
    font-size: .9rem;
  }

  .account-profile-readonly {
    padding: 1rem 1.05rem;
    border: 1px solid rgba(148, 163, 184, 0.16);
    border-radius: 1rem;
    background: rgba(248, 250, 252, 0.72);
    height: 100%;
  }

  .account-profile-readonly small {
    display: block;
    margin-bottom: .3rem;
    color: #8d93ac;
  }

  .account-profile-readonly strong {
    color: #1f2937;
    font-size: 1rem;
  }
</style>
@endsection

@section('content')
@php
  $initials = collect(explode(' ', trim((string) $user->name)))
    ->filter()
    ->take(2)
    ->map(fn ($part) => strtoupper(mb_substr($part, 0, 1)))
    ->implode('');
  $primaryRole = $user->getRoleNames()->first() ?: 'user';
@endphp

<div class="account-profile-page">
  @include('partials.app-breadcrumb', [
    'items' => [
      ['label' => 'Dashboard', 'url' => route('dashboard')],
      ['label' => 'Profil Saya', 'current' => true],
    ],
  ])

  @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
      {{ session('status') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card account-profile-hero">
    <div class="card-body p-4 p-xl-5">
      <div class="row g-4 align-items-center">
        <div class="col-xl-8">
          <div class="d-flex align-items-center gap-3 mb-3">
            <span class="account-profile-avatar">{{ $initials ?: 'U' }}</span>
            <div>
              <div class="account-profile-badge">{{ ucfirst($primaryRole) }}</div>
              <h2 class="text-white mb-1 mt-3">{{ $user->name }}</h2>
              <div class="account-profile-meta">{{ $user->email }}</div>
            </div>
          </div>
          <p class="text-white-50 mb-0">Kelola identitas akun untuk akses {{ $primaryRole === 'mentor' ? 'bimbingan intern' : 'operasional IMS' }} dari satu halaman yang lebih ringkas.</p>
        </div>
        <div class="col-xl-4">
          <div class="row g-3">
            <div class="col-sm-6 col-xl-12">
              <div class="account-profile-readonly">
                <small>Role</small>
                <strong>{{ ucfirst($primaryRole) }}</strong>
              </div>
            </div>
            <div class="col-sm-6 col-xl-12">
              <div class="account-profile-readonly">
                <small>Divisi</small>
                <strong>{{ $user->division?->name ?? 'Tidak terhubung ke divisi' }}</strong>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card account-profile-form-card">
    <div class="row g-4">
      <div class="col-lg-7">
        <div class="mb-4">
          <div class="account-profile-section-title">Informasi Akun</div>
          <div class="account-profile-section-note">Perbarui nama dan email yang dipakai untuk login.</div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST">
          @csrf
          @method('PUT')

          <div class="row g-3">
            <div class="col-12">
              <label for="name" class="form-label">Nama Lengkap</label>
              <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12">
              <label for="email" class="form-label">Email Login</label>
              <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="password" class="form-label">Password Baru</label>
              <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kosongkan jika tidak diubah">
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
              <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
            </div>
          </div>

          <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
          </div>
        </form>
      </div>

      <div class="col-lg-5">
        <div class="mb-4">
          <div class="account-profile-section-title">Ringkasan Akses</div>
          <div class="account-profile-section-note">Info singkat akun dan konteks akses Anda saat ini.</div>
        </div>

        <div class="d-grid gap-3">
          <div class="account-profile-readonly">
            <small>Role Aktif</small>
            <strong>{{ ucfirst($primaryRole) }}</strong>
          </div>
          <div class="account-profile-readonly">
            <small>Divisi</small>
            <strong>{{ $user->division?->name ?? 'Tidak terhubung ke divisi' }}</strong>
          </div>
          <div class="account-profile-readonly">
            <small>Terakhir Diperbarui</small>
            <strong>{{ $user->updated_at?->translatedFormat('d M Y, H:i') ?? '-' }}</strong>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
