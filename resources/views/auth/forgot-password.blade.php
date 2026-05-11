@extends('layouts.app')

@section('title', 'Forgot Password IMS')
@section('page_heading', 'Forgot Password')

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
                    <p class="eyebrow">Password Recovery</p>
                    <h3>Reset password Anda</h3>
                    <p class="panel-subtitle">Masukkan email akun IMS. Kami akan mengirim link reset password yang aman ke inbox Anda.</p>
                </div>

                <form action="{{ route('password.email') }}" method="POST" class="auth-form" data-auth-form>
                    @csrf

                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-shell {{ $errors->has('email') ? 'has-error' : '' }}">
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="nama@ims.co.id"
                                autocomplete="email"
                                required
                                autofocus
                                aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                aria-describedby="{{ $errors->has('email') ? 'email-error' : 'email-help' }}">
                        </div>
                        <small id="email-help" class="field-hint">Gunakan email yang terdaftar pada akun IMS Anda.</small>
                        @error('email')
                            <small id="email-error" class="form-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="button auth-submit-button" data-submit-button data-loading-text="Sending reset link...">
                        <span class="button-spinner" aria-hidden="true"></span>
                        <span class="button-label">Send reset link</span>
                    </button>
                </form>

                <div class="auth-link-row">
                    <a href="{{ route('login') }}" class="text-link">Kembali ke login</a>
                    <span class="auth-session-note">Secure recovery flow</span>
                </div>
            </div>
        </div>
    </section>
@endsection
