@extends('layouts.app')

@section('title', 'Reset Password IMS')
@section('page_heading', 'Reset Password')

@section('content')
    <section class="auth-cover auth-cover-narrow">
        <div class="auth-cover-brand">
            <img src="{{ asset('assets/img/branding/logo.png') }}" alt="IMS Logo">
            <div>
                <strong>IMS</strong>
                <span>Intern Management System</span>
            </div>
        </div>

        <div class="auth-simple-shell">
            <div class="auth-form-card auth-form-card-centered">
                <div class="auth-form-header">
                    <p class="eyebrow">Create New Password</p>
                    <h3>Buat password baru</h3>
                    <p class="panel-subtitle">Masukkan password baru yang kuat untuk melanjutkan akses ke workspace IMS Anda.</p>
                </div>

                <form action="{{ route('password.update') }}" method="POST" class="auth-form" data-auth-form>
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-shell {{ $errors->has('email') ? 'has-error' : '' }}">
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $request->email) }}"
                                placeholder="nama@ims.co.id"
                                autocomplete="email"
                                required
                                autofocus
                                aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                aria-describedby="{{ $errors->has('email') ? 'email-error' : 'email-help' }}">
                        </div>
                        <small id="email-help" class="field-hint">Gunakan email yang menerima link reset password.</small>
                        @error('email')
                            <small id="email-error" class="form-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="reset_password">Password baru</label>
                        <div class="input-shell {{ $errors->has('password') ? 'has-error' : '' }}">
                            <input
                                id="reset_password"
                                type="password"
                                name="password"
                                placeholder="Masukkan password baru"
                                autocomplete="new-password"
                                required
                                aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                                aria-describedby="{{ $errors->has('password') ? 'password-error' : 'password-help' }}">
                            <button
                                type="button"
                                class="input-action"
                                data-password-toggle
                                data-password-target="reset_password"
                                aria-controls="reset_password"
                                aria-label="Tampilkan password"
                                aria-pressed="false">
                                Show
                            </button>
                        </div>
                        <small id="password-help" class="field-hint">Gunakan kombinasi yang kuat dan tidak mudah ditebak.</small>
                        @error('password')
                            <small id="password-error" class="form-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi password baru</label>
                        <div class="input-shell">
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                placeholder="Ulangi password baru"
                                autocomplete="new-password"
                                required>
                            <button
                                type="button"
                                class="input-action"
                                data-password-toggle
                                data-password-target="password_confirmation"
                                aria-controls="password_confirmation"
                                aria-label="Tampilkan password"
                                aria-pressed="false">
                                Show
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="button auth-submit-button" data-submit-button data-loading-text="Resetting password...">
                        <span class="button-spinner" aria-hidden="true"></span>
                        <span class="button-label">Reset password</span>
                    </button>
                </form>

                <div class="auth-link-row">
                    <a href="{{ route('login') }}" class="text-link">Kembali ke login</a>
                    <span class="auth-session-note">Enterprise-grade access recovery</span>
                </div>
            </div>
        </div>
    </section>
@endsection
