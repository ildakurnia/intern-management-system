@extends('layouts/contentNavbarLayout')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card shadow-sm border-0">
      <div class="card-header d-flex align-items-center justify-content-between border-bottom py-4">
        <h5 class="m-0 text-primary fw-bold">Form Tambah Pengguna</h5>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
          <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
      </div>
      <div class="card-body py-5">
        <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm">
          @csrf

          <div class="row g-4">
            {{-- Name --}}
            <div class="col-12">
              <div class="form-floating form-floating-outline">
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                  placeholder="John Doe" value="{{ old('name') }}" required autofocus />
                <label for="name">Nama Lengkap</label>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            {{-- Email --}}
            <div class="col-12">
              <div class="form-floating form-floating-outline">
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                  placeholder="john@example.com" value="{{ old('email') }}" required />
                <label for="email">Alamat Email</label>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            {{-- Role --}}
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                  <option value="">Pilih Role</option>
                  @foreach($roles as $role)
                  <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                    {{ ucfirst($role->name) }}
                  </option>
                  @endforeach
                </select>
                <label for="role">Hak Akses (Role)</label>
                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            {{-- Division --}}
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <select class="form-select @error('division_id') is-invalid @enderror" id="division_id" name="division_id">
                  <option value="">Pilih Divisi</option>
                  @foreach($divisions as $division)
                  <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                    {{ $division->name }}
                  </option>
                  @endforeach
                </select>
                <label for="division_id">Divisi / Departemen</label>
                @error('division_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <small class="text-body-secondary mt-1 d-block" id="division_hint">Wajib untuk role Mentor dan Intern</small>
            </div>

            {{-- Password --}}
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                  name="password" placeholder="············" required />
                <label for="password">Password</label>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            {{-- Password Confirm --}}
            <div class="col-md-6">
              <div class="form-floating form-floating-outline">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                  placeholder="············" required />
                <label for="password_confirmation">Konfirmasi Password</label>
              </div>
            </div>

            {{-- ===== SECTION KHUSUS INTERN (hidden by default) ===== --}}
            <div id="intern_section" class="col-12 d-none">
              <hr class="my-2">
              <div class="d-flex align-items-center gap-2 mb-4">
                <div class="avatar avatar-sm">
                  <span class="avatar-initial bg-label-success rounded"><i class="ri-graduation-cap-line"></i></span>
                </div>
                <div>
                  <h6 class="mb-0">Data Anak Magang</h6>
                  <small class="text-body-secondary">Field berikut wajib diisi untuk role Intern</small>
                </div>
              </div>

              <div class="row g-4">
                {{-- Tipe intern --}}
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                      <option value="">Pilih Tipe</option>
                      <option value="siswa" {{ old('type') == 'siswa' ? 'selected' : '' }}>Siswa (SMK/SMA)</option>
                      <option value="mahasiswa" {{ old('type') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    </select>
                    <label for="type">Tipe Anak Magang</label>
                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>

                {{-- NIM / NISN --}}
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control @error('identification_number') is-invalid @enderror" 
                      id="identification_number" name="identification_number"
                      placeholder="Contoh: 102210xx" value="{{ old('identification_number') }}" />
                    <label for="identification_number text-uppercase" id="id_label">NIM / NISN</label>
                    @error('identification_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>

                {{-- Institusi --}}
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control @error('institution') is-invalid @enderror" id="institution"
                      name="institution" placeholder="Universitas/Sekolah..." value="{{ old('institution') }}" />
                    <label for="institution">Asal Institusi</label>
                    @error('institution') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>

                {{-- Jurusan --}}
                <div class="col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" id="major" name="major"
                      placeholder="Teknik Informatika..." value="{{ old('major') }}" />
                    <label for="major">Jurusan / Program Studi</label>
                  </div>
                </div>

                {{-- Tanggal Mulai --}}
                <div class="col-md-3">
                  <div class="form-floating form-floating-outline">
                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date"
                      name="start_date" value="{{ old('start_date') }}" />
                    <label for="start_date">Tanggal Mulai</label>
                    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>

                {{-- Tanggal Selesai --}}
                <div class="col-md-3">
                  <div class="form-floating form-floating-outline">
                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date"
                      name="end_date" value="{{ old('end_date') }}" />
                    <label for="end_date">Tanggal Selesai</label>
                    @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>
                </div>
              </div>
            </div>
            {{-- ===== END INTERN SECTION ===== --}}

            <div class="col-12 mt-5">
              <button type="submit" class="btn btn-primary w-100 py-3 shadow">
                <i class="ri-save-line me-2"></i> Simpan Pengguna Baru
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const roleSelect    = document.getElementById('role');
  const internSection = document.getElementById('intern_section');
  const typeSelect    = document.getElementById('type');
  const institutionInput = document.getElementById('institution');
  const startDateInput   = document.getElementById('start_date');
  const endDateInput     = document.getElementById('end_date');

  function toggleInternSection() {
    const isIntern = roleSelect.value === 'intern';
    internSection.classList.toggle('d-none', !isIntern);

    // Toggle required attributes
    typeSelect.required        = isIntern;
    institutionInput.required  = isIntern;
    startDateInput.required    = isIntern;
    endDateInput.required      = isIntern;
  }

  roleSelect.addEventListener('change', toggleInternSection);

  // Trigger on page load jika old('role') = intern (validasi gagal)
  toggleInternSection();
});
</script>
@endsection
