@extends('layouts/contentNavbarLayout')

@section('title', 'Lengkapi Profil')

@php
  $institutionService = app(\App\Services\InstitutionService::class);
  $selectedInstitutionId = old('institution_id', $intern->institution_id);
  $requiresBankAccount = $institutionService->requiresBankAccount($intern->type, $selectedInstitutionId);

  $profileFieldChecks = [
      filled(old('phone', $intern->phone)),
      filled(old('birth_date', optional($intern->birth_date)->format('Y-m-d'))),
      filled(old('gender', $intern->gender)),
      filled($selectedInstitutionId) || filled(old('institution_manual_name', $intern->institution_manual_name ?? $intern->institution)),
      filled(old('major', $intern->major)),
      filled(old('address', $intern->address)),
  ];

  if ($intern->type === 'mahasiswa') {
      $profileFieldChecks[] = filled(old('semester', $intern->semester));
  }

  if ($intern->type === 'siswa') {
      $profileFieldChecks[] = filled(old('school_grade', $intern->school_grade));
  }

  if ($requiresBankAccount) {
      $profileFieldChecks[] = filled(old('bank_account_number', $intern->bank_account_number));
  }

  $profileCompletion = count($profileFieldChecks) > 0
      ? (int) round((collect($profileFieldChecks)->filter()->count() / count($profileFieldChecks)) * 100)
      : 0;

  $profileModeLabel = $intern->hasCompletedProfile() ? 'Aktif Magang' : 'Langkah 1 dari 2';
  $profileStatusLabel = match ($intern->registration_status) {
      'approved' => 'Aktif Magang',
      'pending' => 'Menunggu Verifikasi',
      'rejected' => 'Perlu Revisi',
      default => 'Lengkapi Profil',
  };

  $avatarUrl = $intern->photo ? \Illuminate\Support\Facades\Storage::disk('public')->url($intern->photo) : null;
  $initials = collect(explode(' ', trim((string) $intern->name)))
      ->filter()
      ->take(2)
      ->map(fn ($part) => strtoupper(mb_substr($part, 0, 1)))
      ->implode('');
@endphp

@section('page-style')
  <style>
    .intern-profile-page {
      display: grid;
      gap: 1rem;
    }

    .intern-profile-shell {
      border: 1px solid rgba(148, 163, 184, 0.14);
      border-radius: 1.55rem;
      background: rgba(255, 255, 255, 0.97);
      box-shadow: 0 16px 42px rgba(15, 23, 42, 0.06);
    }

    .intern-profile-hero {
      padding: 1.35rem;
    }

    .intern-profile-hero-grid {
      display: grid;
      grid-template-columns: auto minmax(0, 1fr) auto;
      gap: 1rem;
      align-items: center;
    }

    .intern-profile-avatar {
      width: 4.7rem;
      height: 4.7rem;
      border-radius: 1.2rem;
      overflow: hidden;
      background: linear-gradient(135deg, #1d4ed8, #2563eb);
      color: #fff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.45rem;
      font-weight: 800;
      box-shadow: 0 16px 28px rgba(37, 99, 235, 0.22);
    }

    .intern-profile-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .intern-profile-heading {
      min-width: 0;
    }

    .intern-profile-name-row {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 0.65rem;
      margin-bottom: 0.25rem;
    }

    .intern-profile-name {
      margin: 0;
      color: #172033;
      font-size: clamp(1.3rem, 2vw, 1.6rem);
      font-weight: 800;
      letter-spacing: -0.03em;
    }

    .intern-profile-badge {
      display: inline-flex;
      align-items: center;
      padding: 0.38rem 0.7rem;
      border-radius: 999px;
      background: rgba(37, 99, 235, 0.1);
      color: #2563eb;
      font-size: 0.74rem;
      font-weight: 800;
    }

    .intern-profile-desc {
      margin: 0;
      color: #64748b;
      font-size: 0.92rem;
      line-height: 1.6;
    }

    .intern-profile-side {
      text-align: right;
    }

    .intern-profile-side small {
      display: block;
      color: #94a3b8;
      font-size: 0.76rem;
      font-weight: 700;
    }

    .intern-profile-side strong {
      color: #334155;
      font-size: 0.84rem;
      font-weight: 800;
    }

    .intern-profile-progress-wrap {
      margin-top: 1rem;
    }

    .intern-profile-progress-label {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: 0.45rem;
      color: #64748b;
      font-size: 0.8rem;
      font-weight: 700;
    }

    .intern-profile-progress {
      width: 100%;
      height: 0.55rem;
      overflow: hidden;
      border-radius: 999px;
      background: rgba(226, 232, 240, 0.95);
    }

    .intern-profile-progress > span {
      display: block;
      height: 100%;
      border-radius: inherit;
      background: linear-gradient(90deg, #1d4ed8, #2563eb);
    }

    .intern-profile-form {
      padding: 1.1rem 1.35rem 1.35rem;
    }

    .intern-profile-form > form > .row {
      margin-top: 0;
    }

    .intern-profile-section {
      height: 100%;
      padding: 1.55rem 1.2rem 1.2rem;
      border: 1px solid rgba(148, 163, 184, 0.14);
      border-radius: 1.35rem;
      background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.95));
    }

    .intern-profile-section-title {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      margin-bottom: 1rem;
      color: #111827;
      font-size: 1rem;
      font-weight: 800;
      letter-spacing: -0.02em;
    }

    .intern-profile-section-title i {
      width: 2rem;
      height: 2rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 0.85rem;
      background: rgba(37, 99, 235, 0.1);
      color: #2563eb;
      font-size: 1rem;
    }

    .intern-profile-section-note {
      margin: -0.35rem 0 0.9rem;
      color: #94a3b8;
      font-size: 0.82rem;
      line-height: 1.6;
    }

    .intern-profile-photo-box {
      display: grid;
      place-items: center;
      min-height: 13.5rem;
      padding: 1.25rem;
      border: 1px dashed rgba(148, 163, 184, 0.45);
      border-radius: 1.2rem;
      background: rgba(248, 250, 252, 0.78);
      text-align: center;
    }

    .intern-profile-photo-box h4 {
      margin: 0;
      color: #1f2937;
      font-size: 0.98rem;
      font-weight: 800;
    }

    .intern-profile-photo-box p {
      margin: 0.45rem 0 0.95rem;
      color: #94a3b8;
      font-size: 0.82rem;
      line-height: 1.6;
    }

    .intern-profile-footer {
      display: flex;
      justify-content: flex-end;
      gap: 0.8rem;
      margin-top: 1.15rem;
    }

    .intern-profile-footer .btn {
      min-width: 10.5rem;
    }

    @media (max-width: 991.98px) {
      .intern-profile-hero-grid {
        grid-template-columns: auto minmax(0, 1fr);
      }

      .intern-profile-side {
        grid-column: 1 / -1;
        text-align: left;
      }
    }

    @media (max-width: 767.98px) {
      .intern-profile-hero,
      .intern-profile-form,
      .intern-profile-section {
        padding: 1rem;
      }

      .intern-profile-hero-grid {
        grid-template-columns: 1fr;
      }

      .intern-profile-avatar {
        width: 4rem;
        height: 4rem;
        border-radius: 1rem;
      }

      .intern-profile-footer {
        flex-direction: column-reverse;
      }

      .intern-profile-footer .btn {
        width: 100%;
      }
    }
  </style>
@endsection

@section('content')
  <div class="intern-profile-page">
    @if (session('status'))
      <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="intern-profile-shell intern-profile-hero">
      <div class="intern-profile-hero-grid">
        <div class="intern-profile-avatar">
          @if ($avatarUrl)
            <img src="{{ $avatarUrl }}" alt="{{ $intern->name }}">
          @else
            <span>{{ $initials ?: 'IN' }}</span>
          @endif
        </div>

        <div class="intern-profile-heading">
          <div class="intern-profile-name-row">
            <h1 class="intern-profile-name">{{ $intern->name }}</h1>
            <span class="intern-profile-badge">{{ $profileStatusLabel }}</span>
          </div>
          <p class="intern-profile-desc">
            Lengkapi profil untuk mempercepat proses verifikasi data magang dan memastikan proses onboarding berjalan lancar.
          </p>

          <div class="intern-profile-progress-wrap">
            <div class="intern-profile-progress-label">
              <span>Completion Progress</span>
              <span>{{ $profileCompletion }}%</span>
            </div>
            <div class="intern-profile-progress">
              <span style="width: {{ $profileCompletion }}%;"></span>
            </div>
          </div>
        </div>

        <div class="intern-profile-side">
          <small>{{ $intern->hasCompletedProfile() ? 'Terakhir diperbarui' : 'Status onboarding' }}</small>
          <strong>
            {{ $intern->hasCompletedProfile() && $intern->updated_at ? $intern->updated_at->translatedFormat('d M Y, H:i') : $profileModeLabel }}
          </strong>
        </div>
      </div>
    </div>

    <div class="intern-profile-shell intern-profile-form">
      <form action="{{ route('intern.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
          <div class="col-xl-6">
            <div class="intern-profile-section">
              <div class="intern-profile-section-title">
                <i class="ri ri-user-3-line"></i>
                <span>Informasi Pribadi</span>
              </div>

              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label">Nama Lengkap</label>
                  <input type="text" class="form-control bg-lighter" value="{{ $intern->name }}" disabled>
                </div>

                <div class="col-md-6">
                  <label class="form-label">{{ $intern->type === 'mahasiswa' ? 'NIM' : 'NIS' }}</label>
                  <input type="text" class="form-control bg-lighter" value="{{ $intern->nim ?? $intern->nis }}" disabled>
                </div>

                <div class="col-md-6">
                  <label for="phone" class="form-label">No HP <span class="text-danger">*</span></label>
                  <input id="phone" type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $intern->phone) }}" placeholder="Contoh: 081234567890" required>
                  @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                  <label for="birth_date" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                  <input id="birth_date" type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date', optional($intern->birth_date)->format('Y-m-d')) }}" required>
                  @error('birth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                  <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                  <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                    <option value="">Pilih</option>
                    <option value="male" @selected(old('gender', $intern->gender) === 'male')>Laki-laki</option>
                    <option value="female" @selected(old('gender', $intern->gender) === 'female')>Perempuan</option>
                  </select>
                  @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                  <label for="address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                  <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="4" placeholder="Batam, Kepulauan Riau, Indonesia" required>{{ old('address', $intern->address) }}</textarea>
                  @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                  <hr class="my-2">
                  <div class="intern-profile-section-title mb-2">
                    <i class="ri ri-camera-3-line"></i>
                    <span>Foto Profil</span>
                  </div>

                  <div class="intern-profile-photo-box">
                    <div>
                      <h4>Pilih Foto Profil Baru</h4>
                      <p>Unggah foto formal atau semi formal dengan ukuran maksimal 2MB dalam format JPG, JPEG, atau PNG.</p>
                      <input id="photo" class="form-control @error('photo') is-invalid @enderror" type="file" name="photo" accept="image/*">
                      @error('photo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-6">
            <div class="intern-profile-section">
              <div class="intern-profile-section-title">
                <i class="ri ri-graduation-cap-line"></i>
                <span>Informasi Akademik</span>
              </div>

              <div class="intern-profile-section-note">
                Sesuaikan data akademik yang masih aktif dan dipakai selama proses magang.
              </div>

              <div class="row g-3">
                @include('partials.forms.institution-picker', [
                    'pickerId' => 'intern_institution',
                    'label' => 'Asal Sekolah/Kampus',
                    'required' => true,
                    'selectedInstitutionId' => old('institution_id', $intern->institution_id),
                    'selectedInstitutionLabel' => $intern->institutionReference?->name,
                    'manualInstitutionName' => old('institution_manual_name', $intern->institution_manual_name ?? $intern->institution),
                    'wrapperClass' => 'col-12',
                    'inputPlaceholder' => 'Ketik nama sekolah atau kampus',
                ])

                <div class="col-12">
                  <label for="major" class="form-label">Jurusan <span class="text-danger">*</span></label>
                  <input id="major" type="text" name="major" class="form-control @error('major') is-invalid @enderror" value="{{ old('major', $intern->major) }}" placeholder="Sistem Informasi, Teknik Mesin, dll." required>
                  @error('major') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                @if ($intern->type === 'siswa')
                  <div class="col-12">
                    <label for="school_grade" class="form-label">Kelas <span class="text-danger">*</span></label>
                    <input id="school_grade" type="text" name="school_grade" class="form-control @error('school_grade') is-invalid @enderror" value="{{ old('school_grade', $intern->school_grade) }}" placeholder="Misal: XII RPL 1" required>
                    @error('school_grade') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                @else
                  <div class="col-12">
                    <label for="faculty" class="form-label">Fakultas</label>
                    <input id="faculty" type="text" name="faculty" class="form-control @error('faculty') is-invalid @enderror" value="{{ old('faculty', $intern->faculty) }}" placeholder="Misal: Informatika">
                    @error('faculty') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>

                  <div class="col-12">
                    <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
                    <input id="semester" type="text" name="semester" class="form-control @error('semester') is-invalid @enderror" value="{{ old('semester', $intern->semester) }}" placeholder="Misal: 6" required>
                    @error('semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                @endif

                <div class="col-12 {{ $requiresBankAccount ? '' : 'd-none' }}" id="bank_account_card">
                  <label for="bank_account_number" class="form-label">No Rekening <span class="text-danger">*</span></label>
                  <input
                    id="bank_account_number"
                    type="text"
                    name="bank_account_number"
                    class="form-control @error('bank_account_number') is-invalid @enderror"
                    value="{{ old('bank_account_number', $intern->bank_account_number) }}"
                    placeholder="Masukkan nomor rekening">
                  @error('bank_account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  <div class="form-text">Wajib diisi khusus mahasiswa Politeknik Negeri Batam untuk proses uang saku.</div>
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="intern-profile-footer">
          @if ($intern->hasCompletedProfile())
            <a href="{{ route('dashboard.intern') }}" class="btn btn-outline-secondary">Batal</a>
          @endif
          <button type="submit" class="btn btn-primary d-inline-flex align-items-center justify-content-center">
            <i class="icon-base ri ri-save-line me-2"></i>
            {{ $intern->hasCompletedProfile() ? 'Simpan Perubahan' : 'Simpan & Lanjutkan' }}
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('page-script')
  @include('partials.forms.institution-picker-script')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const eligibleInstitutionId = '{{ $institutionService->getAllowanceEligibleInstitutionId() }}';
      const institutionIdInput = document.getElementById('intern_institution_id');
      const bankAccountCard = document.getElementById('bank_account_card');
      const bankAccountInput = document.getElementById('bank_account_number');

      function syncBankAccountVisibility() {
        const shouldShow = institutionIdInput && institutionIdInput.value && institutionIdInput.value === eligibleInstitutionId;

        bankAccountCard.classList.toggle('d-none', !shouldShow);
        bankAccountInput.required = shouldShow;

        if (!shouldShow) {
          bankAccountInput.value = '';
        }
      }

      institutionIdInput?.addEventListener('change', syncBankAccountVisibility);
      institutionIdInput?.addEventListener('input', syncBankAccountVisibility);
      document.getElementById('intern_institution_search')?.addEventListener('change', () => setTimeout(syncBankAccountVisibility, 60));
      document.getElementById('intern_institution_search')?.addEventListener('input', () => setTimeout(syncBankAccountVisibility, 60));

      syncBankAccountVisibility();
    });
  </script>
@endsection
