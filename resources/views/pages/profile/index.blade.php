@extends('layouts/contentNavbarLayout')

@section('title', 'My Profile')

@section('page-style')
<style>
  .user-profile-header-banner img {
    width: 100%;
    object-fit: cover;
    height: 250px;
  }
  .user-profile-img {
    border: 5px solid #fff;
    width: 120px;
    height: 120px;
    object-fit: cover;
  }
  .user-profile-header {
    margin-top: -2rem;
  }
</style>
@endsection

@section('content')
  <!-- Header -->
  <div class="row">
    <div class="col-12">
      <div class="card mb-6">
        <div class="user-profile-header-banner">
          <img src="{{ asset('assets/img/pages/profile-banner.png') }}" alt="Banner image" class="rounded-top" />
        </div>
        <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-5">
          <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
            @php
              $avatar = 'assets/img/avatars/1.png'; // default
              if ($user->hasRole('admin') || $user->hasRole('superadmin')) $avatar = 'assets/img/avatars/2.png';
              elseif ($user->hasRole('mentor')) $avatar = 'assets/img/avatars/3.png';
              elseif ($user->hasRole('intern') && isset($intern) && $intern->photo_path) {
                  $avatar = 'storage/' . $intern->photo_path;
              }
            @endphp
            <img src="{{ asset($avatar) }}" alt="user image"
              class="d-block h-auto ms-0 ms-sm-5 rounded-4 user-profile-img shadow-sm" />
          </div>
          <div class="flex-grow-1 mt-4 mt-sm-12">
            <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-6">
              <div class="user-profile-info">
                <h4 class="mb-2">{{ $user->name }}</h4>
                <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4">
                  <li class="list-inline-item"><i class="icon-base ri-shield-user-line me-2 icon-24px"></i><span class="fw-medium">{{ $roleName }}</span></li>
                  <li class="list-inline-item"><i class="icon-base ri-community-line me-2 icon-24px"></i><span class="fw-medium">{{ $divisionName }}</span></li>
                  <li class="list-inline-item"><i class="icon-base ri-calendar-line me-2 icon-24px"></i><span class="fw-medium">Joined {{ $user->created_at->format('M Y') }}</span></li>
                </ul>
              </div>
              
              @if($user->hasRole('intern'))
                <a href="{{ route('intern.profile.edit') }}" class="btn btn-primary">
                  <i class="icon-base ri-edit-box-line icon-16px me-2"></i>Edit Profil
                </a>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Header -->

  <!-- User Profile Content -->
  <div class="row">
    <div class="col-xl-4 col-lg-5 col-md-5">
      <!-- About User -->
      <div class="card mb-6">
        <div class="card-body">
          <small class="card-text text-uppercase text-body-secondary small">Tentang</small>
          <ul class="list-unstyled my-3 py-1">
            <li class="d-flex align-items-center mb-4"><i class="icon-base ri-user-3-line icon-24px"></i><span class="fw-medium mx-2">Nama Lengkap:</span> <span>{{ $user->name }}</span></li>
            <li class="d-flex align-items-center mb-4"><i class="icon-base ri-check-line icon-24px"></i><span class="fw-medium mx-2">Status:</span> <span>Aktif</span></li>
            <li class="d-flex align-items-center mb-4"><i class="icon-base ri-star-smile-line icon-24px"></i><span class="fw-medium mx-2">Role:</span> <span>{{ $roleName }}</span></li>
          </ul>
          
          <small class="card-text text-uppercase text-body-secondary small">Kontak</small>
          <ul class="list-unstyled my-3 py-1">
            <li class="d-flex align-items-center mb-4"><i class="icon-base ri-mail-open-line icon-24px"></i><span class="fw-medium mx-2">Email:</span> <span>{{ $user->email }}</span></li>
            @if($user->hasRole('intern') && isset($intern))
              <li class="d-flex align-items-center mb-4"><i class="icon-base ri-phone-line icon-24px"></i><span class="fw-medium mx-2">No HP:</span> <span>{{ $intern->phone ?? '-' }}</span></li>
            @endif
          </ul>

          @if($user->hasRole('intern') && isset($intern))
            <small class="card-text text-uppercase text-body-secondary small">Akademik</small>
            <ul class="list-unstyled mb-0 mt-3 pt-1">
              <li class="d-flex align-items-center mb-4">
                <i class="icon-base ri-building-line icon-24px text-body me-2"></i>
                <div class="d-flex flex-wrap"><span class="fw-medium me-2">Asal:</span><span>{{ $intern->institution ?? '-' }}</span></div>
              </li>
              <li class="d-flex align-items-center mb-4">
                <i class="icon-base ri-book-read-line icon-24px text-body me-2"></i>
                <div class="d-flex flex-wrap"><span class="fw-medium me-2">Jurusan:</span><span>{{ $intern->major ?? '-' }}</span></div>
              </li>
            </ul>
          @endif
        </div>
      </div>
      <!--/ About User -->
    </div>
    
    <div class="col-xl-8 col-lg-7 col-md-7">
      <!-- Profile Overview -->
      <div class="card mb-6">
        <div class="card-header align-items-center">
          <h5 class="card-action-title mb-0"><i class="icon-base ri-bar-chart-2-line icon-24px text-body me-4"></i>Ringkasan Aktivitas</h5>
        </div>
        <div class="card-body">
          <div class="row">
            @if($user->hasRole('intern'))
              <div class="col-md-4 col-sm-6 mb-4">
                <div class="d-flex align-items-center">
                  <div class="avatar me-3">
                    <span class="avatar-initial rounded bg-label-primary"><i class="ri-draft-line icon-24px"></i></span>
                  </div>
                  <div>
                    <h5 class="mb-0">{{ $logbookCount }}</h5>
                    <small>Total Logbook</small>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-6 mb-4">
                <div class="d-flex align-items-center">
                  <div class="avatar me-3">
                    <span class="avatar-initial rounded bg-label-success"><i class="ri-calendar-line icon-24px"></i></span>
                  </div>
                  <div>
                    <h5 class="mb-0">{{ $startDate }}</h5>
                    <small>Mulai Magang</small>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-6 mb-4">
                <div class="d-flex align-items-center">
                  <div class="avatar me-3">
                    <span class="avatar-initial rounded bg-label-warning"><i class="ri-time-line icon-24px"></i></span>
                  </div>
                  <div>
                    <h5 class="mb-0">{{ $status }}</h5>
                    <small>Status Program</small>
                  </div>
                </div>
              </div>
            @elseif($user->hasRole('mentor'))
              <div class="col-md-6 col-sm-6 mb-4">
                <div class="d-flex align-items-center">
                  <div class="avatar me-3">
                    <span class="avatar-initial rounded bg-label-info"><i class="ri-group-line icon-24px"></i></span>
                  </div>
                  <div>
                    <h5 class="mb-0">{{ $internsCount }}</h5>
                    <small>Interns Bimbingan</small>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 mb-4">
                <div class="d-flex align-items-center">
                  <div class="avatar me-3">
                    <span class="avatar-initial rounded bg-label-primary"><i class="ri-building-line icon-24px"></i></span>
                  </div>
                  <div>
                    <h5 class="mb-0">{{ $divisionName }}</h5>
                    <small>Divisi</small>
                  </div>
                </div>
              </div>
            @else
              <div class="col-12">
                <p class="text-body-secondary">Anda masuk sebagai Administrator sistem. Untuk mengelola data, silakan kembali ke <a href="{{ route('dashboard') }}">Dashboard Utama</a>.</p>
              </div>
            @endif
          </div>
        </div>
      </div>
      <!--/ Profile Overview -->
    </div>
  </div>
  <!--/ User Profile Content -->
@endsection
