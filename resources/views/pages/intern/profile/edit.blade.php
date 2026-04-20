@extends('layouts.app')

@section('title', 'Lengkapi Profil')
@section('page_heading', 'Lengkapi Profil')

@section('content')
    <section class="page-intro card-surface">
        <div>
            <p class="eyebrow">Intern Onboarding</p>
            <h2>Lengkapi Profil</h2>
            <p>Profil wajib dilengkapi sebelum kamu bisa upload berkas dan masuk dashboard.</p>
        </div>
        <span class="intro-badge">Langkah 1 dari 2</span>
    </section>

    <section class="card-surface form-card">
        <form action="{{ route('intern.profile.update') }}" method="POST" enctype="multipart/form-data" class="auth-form form-grid">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama</label>
                <input type="text" value="{{ $intern->name }}" disabled>
            </div>

            <div class="form-group">
                <label>{{ $intern->type === 'mahasiswa' ? 'NIM' : 'NIS' }}</label>
                <input type="text" value="{{ $intern->nim ?? $intern->nis }}" disabled>
            </div>

            <div class="form-group">
                <label for="phone">No HP</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone', $intern->phone) }}" required>
                @error('phone') <small class="form-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="birth_date">Tanggal Lahir</label>
                <input id="birth_date" type="date" name="birth_date" value="{{ old('birth_date', optional($intern->birth_date)->format('Y-m-d')) }}" required>
                @error('birth_date') <small class="form-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="gender">Jenis Kelamin</label>
                <select id="gender" name="gender" required>
                    <option value="">Pilih</option>
                    <option value="male" @selected(old('gender', $intern->gender) === 'male')>Laki-laki</option>
                    <option value="female" @selected(old('gender', $intern->gender) === 'female')>Perempuan</option>
                </select>
                @error('gender') <small class="form-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="institution">Asal Sekolah/Kampus</label>
                <input id="institution" type="text" name="institution" value="{{ old('institution', $intern->institution) }}" required>
                @error('institution') <small class="form-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="major">Jurusan</label>
                <input id="major" type="text" name="major" value="{{ old('major', $intern->major) }}" required>
                @error('major') <small class="form-error">{{ $message }}</small> @enderror
            </div>

            @if ($intern->type === 'siswa')
                <div class="form-group">
                    <label for="school_grade">Kelas</label>
                    <input id="school_grade" type="text" name="school_grade" value="{{ old('school_grade', $intern->school_grade) }}" required>
                    @error('school_grade') <small class="form-error">{{ $message }}</small> @enderror
                </div>
            @else
                <div class="form-group">
                    <label for="faculty">Fakultas</label>
                    <input id="faculty" type="text" name="faculty" value="{{ old('faculty', $intern->faculty) }}">
                    @error('faculty') <small class="form-error">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="semester">Semester</label>
                    <input id="semester" type="text" name="semester" value="{{ old('semester', $intern->semester) }}" required>
                    @error('semester') <small class="form-error">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="gpa">IPK</label>
                    <input id="gpa" type="number" step="0.01" min="0" max="4" name="gpa" value="{{ old('gpa', $intern->gpa) }}">
                    @error('gpa') <small class="form-error">{{ $message }}</small> @enderror
                </div>
            @endif

            <div class="form-group form-span">
                <label for="address">Alamat</label>
                <textarea id="address" name="address" rows="3" required>{{ old('address', $intern->address) }}</textarea>
                @error('address') <small class="form-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-group form-span">
                <label for="photo">Foto</label>
                <input id="photo" type="file" name="photo" accept="image/*">
                @error('photo') <small class="form-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-span">
                <button type="submit" class="button">Simpan Profil</button>
            </div>
        </form>
    </section>
@endsection
