@extends('layouts.app')

@section('title', 'Registrasi Intern')
@section('page_heading', 'Registrasi Intern')

@section('content')
    <section class="auth-cover">
        <div class="auth-cover-brand">
            <img src="{{ asset('assets/img/branding/logo.png') }}" alt="IMS Logo">
            <div>
                <strong>IMS</strong>
                <span>Intern Management System</span>
            </div>
        </div>

        <div class="auth-cover-card">
            <div class="auth-cover-visual">
                <div class="auth-visual-copy">
                    <p class="eyebrow">Intern Registration</p>
                    <h2>Daftar memakai data yang sudah diimport admin</h2>
                    <p>Email dan NIM/NIS harus cocok dengan data awal intern. Setelah berhasil, kamu akan diminta melengkapi profil dan berkas wajib.</p>
                </div>

                <div class="auth-feature-list">
                    <div class="feature-item">
                        <span class="feature-badge feature-badge-primary">01</span>
                        <div>
                            <h3>Data Awal</h3>
                            <p>Admin mengimport nama, email, NIM/NIS, divisi, dan periode magang.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <span class="feature-badge feature-badge-success">02</span>
                        <div>
                            <h3>Onboarding</h3>
                            <p>Profil dan berkas wajib diselesaikan sebelum dashboard dibuka.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="auth-form-panel">
                <div class="panel-card">
                    <h3>Register Intern</h3>
                    <p class="panel-subtitle">Gunakan email dan NIM/NIS yang terdaftar oleh admin.</p>

                    <form action="{{ route('intern.register.store') }}" method="POST" class="auth-form">
                        @csrf

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="identifier">NIM / NIS</label>
                            <input id="identifier" type="text" name="identifier" value="{{ old('identifier') }}" required>
                            @error('identifier')
                                <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" required>
                            @error('password')
                                <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="button">Register</button>
                    </form>

                    <p class="panel-subtitle">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 700;">Login</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
