@extends('layouts/contentNavbarLayout')

@section('title', 'Lengkapi Profil')
@section('page_heading', 'Lengkapi Profil')

@section('content')

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between p-4 gap-3">
            <div>
                <p class="text-primary text-uppercase fw-semibold mb-1 small" style="letter-spacing: 0.5px;">{{ $intern->hasCompletedProfile() && $intern->hasCompletedDocuments() ? 'Pengaturan Akun' : 'Intern Onboarding' }}</p>
                <h4 class="mb-1">{{ $intern->hasCompletedProfile() && $intern->hasCompletedDocuments() ? 'Edit Profil & Berkas' : 'Lengkapi Profil & Berkas' }}</h4>
                <p class="text-body-secondary mb-0">{{ $intern->hasCompletedProfile() && $intern->hasCompletedDocuments() ? 'Perbarui data diri, kontak, atau berkas kamu jika ada perubahan.' : 'Profil dan berkas wajib dilengkapi sebelum kamu bisa masuk dashboard.' }}</p>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('intern.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4 mt-1">
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control bg-lighter" value="{{ $intern->name }}" disabled>
                            <label>Nama Lengkap</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control bg-lighter" value="{{ $intern->nim ?? $intern->nis }}" disabled>
                            <label>{{ $intern->type === 'mahasiswa' ? 'NIM' : 'NIS' }}</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input id="phone" type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $intern->phone) }}" placeholder="Contoh: 081234567890" required>
                            <label for="phone">No HP <span class="text-danger">*</span></label>
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input id="birth_date" type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date', optional($intern->birth_date)->format('Y-m-d')) }}" required>
                            <label for="birth_date">Tanggal Lahir <span class="text-danger">*</span></label>
                            @error('birth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                <option value="">Pilih</option>
                                <option value="male" @selected(old('gender', $intern->gender) === 'male')>Laki-laki</option>
                                <option value="female" @selected(old('gender', $intern->gender) === 'female')>Perempuan</option>
                            </select>
                            <label for="gender">Jenis Kelamin <span class="text-danger">*</span></label>
                            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input id="institution" type="text" name="institution" class="form-control @error('institution') is-invalid @enderror" value="{{ old('institution', $intern->institution) }}" placeholder="Nama Sekolah/Kampus" required>
                            <label for="institution">Asal Sekolah/Kampus <span class="text-danger">*</span></label>
                            @error('institution') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input id="major" type="text" name="major" class="form-control @error('major') is-invalid @enderror" value="{{ old('major', $intern->major) }}" placeholder="Jurusan kamu" required>
                            <label for="major">Jurusan <span class="text-danger">*</span></label>
                            @error('major') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    @if ($intern->type === 'siswa')
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input id="school_grade" type="text" name="school_grade" class="form-control @error('school_grade') is-invalid @enderror" value="{{ old('school_grade', $intern->school_grade) }}" placeholder="Misal: XII RPL 1" required>
                                <label for="school_grade">Kelas <span class="text-danger">*</span></label>
                                @error('school_grade') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    @else
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input id="faculty" type="text" name="faculty" class="form-control @error('faculty') is-invalid @enderror" value="{{ old('faculty', $intern->faculty) }}" placeholder="Fakultas kamu">
                                <label for="faculty">Fakultas</label>
                                @error('faculty') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input id="semester" type="text" name="semester" class="form-control @error('semester') is-invalid @enderror" value="{{ old('semester', $intern->semester) }}" placeholder="Misal: 6" required>
                                <label for="semester">Semester <span class="text-danger">*</span></label>
                                @error('semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input id="gpa" type="number" step="0.01" min="0" max="4" name="gpa" class="form-control @error('gpa') is-invalid @enderror" value="{{ old('gpa', $intern->gpa) }}" placeholder="Misal: 3.85">
                                <label for="gpa">IPK</label>
                                @error('gpa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    @endif

                    <div class="col-12">
                        <div class="form-floating form-floating-outline">
                            <textarea id="address" name="address" class="form-control h-px-100 @error('address') is-invalid @enderror" placeholder="Alamat lengkap" required>{{ old('address', $intern->address) }}</textarea>
                            <label for="address">Alamat Lengkap <span class="text-danger">*</span></label>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="photo" class="form-label">Foto Profil (Opsional)</label>
                        <input id="photo" class="form-control @error('photo') is-invalid @enderror" type="file" name="photo" accept="image/*">
                        @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 mt-4">
                        <hr>
                        <h5 class="mb-3">Berkas Wajib</h5>
                    </div>

                    <div class="col-md-6">
                        <label for="ktp" class="form-label">KTP <span class="text-danger">*</span></label>
                        @if($intern->ktp_path)
                            <div class="mb-2"><span class="badge bg-label-success">Sudah diupload</span></div>
                        @endif
                        <input id="ktp" class="form-control @error('ktp') is-invalid @enderror" type="file" name="ktp" accept=".jpg,.jpeg,.png,.pdf" {{ $intern->ktp_path ? '' : 'required' }}>
                        <div class="form-text">Format: JPG, PNG, PDF (Maks 2MB)</div>
                        @error('ktp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="student_card" class="form-label">Kartu Siswa/Mahasiswa <span class="text-danger">*</span></label>
                        @if($intern->student_card_path)
                            <div class="mb-2"><span class="badge bg-label-success">Sudah diupload</span></div>
                        @endif
                        <input id="student_card" class="form-control @error('student_card') is-invalid @enderror" type="file" name="student_card" accept=".jpg,.jpeg,.png,.pdf" {{ $intern->student_card_path ? '' : 'required' }}>
                        <div class="form-text">Format: JPG, PNG, PDF (Maks 2MB)</div>
                        @error('student_card') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="bpjs" class="form-label">BPJS Ketenagakerjaan <span class="text-danger">*</span></label>
                        @if($intern->bpjs_path)
                            <div class="mb-2"><span class="badge bg-label-success">Sudah diupload</span></div>
                        @endif
                        <input id="bpjs" class="form-control @error('bpjs') is-invalid @enderror" type="file" name="bpjs" accept=".jpg,.jpeg,.png,.pdf" {{ $intern->bpjs_path ? '' : 'required' }}>
                        <div class="form-text">Format: JPG, PNG, PDF (Maks 2MB)</div>
                        @error('bpjs') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="recommendation_letter" class="form-label">Surat Pengantar <span class="text-danger">*</span></label>
                        @if($intern->recommendation_letter_path)
                            <div class="mb-2"><span class="badge bg-label-success">Sudah diupload</span></div>
                        @endif
                        <input id="recommendation_letter" class="form-control @error('recommendation_letter') is-invalid @enderror" type="file" name="recommendation_letter" accept=".jpg,.jpeg,.png,.pdf" {{ $intern->recommendation_letter_path ? '' : 'required' }}>
                        <div class="form-text">Format: JPG, PNG, PDF (Maks 2MB)</div>
                        @error('recommendation_letter') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 mt-5">
                        <button type="submit" class="btn btn-primary d-flex align-items-center">
                            <i class="icon-base ri ri-save-line me-2"></i> {{ $intern->hasCompletedProfile() && $intern->hasCompletedDocuments() ? 'Simpan Perubahan' : 'Simpan & Lanjutkan' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
