@extends('layouts.app')

@section('title', 'Login IMS')
@section('page_heading', 'Login')

@section('content')
    <section class="auth-cover auth-cover-enterprise">
        <div class="auth-cover-brand">
            <img src="{{ asset('assets/img/branding/logo.png') }}" alt="IMS Logo">
            <div>
                <strong>IMS</strong>
                <span>Intern Management System</span>
            </div>
            <span class="auth-brand-badge">Enterprise Workspace</span>
        </div>

        <div class="auth-cover-card">
            <div class="auth-cover-visual">
                <div class="auth-visual-copy">
                    <p class="eyebrow">IMS PORTAL MAGANG</p>
                    <h2>Pantau magang dalam satu portal.</h2>
                    <p>Kehadiran, logbook, dan progres intern lebih mudah dipantau.</p>
                </div>

                <div class="auth-preview-shell" aria-hidden="true">
                    <div class="auth-stage-flow">
                        <div class="auth-stage-flow-head">
                            <strong>Alur Magang di IMS</strong>
                            <span>Tahapan utama intern</span>
                        </div>

                        <div class="auth-stage-track">
                            <div class="auth-stage-chip is-complete">
                                <span class="auth-stage-number">1</span>
                                <div class="auth-stage-copy">
                                    <strong>Registrasi</strong>
                                    <small>Aktivasi akun intern</small>
                                </div>
                            </div>

                            <span class="auth-stage-arrow" aria-hidden="true">&rarr;</span>

                            <div class="auth-stage-chip is-active">
                                <span class="auth-stage-number">2</span>
                                <div class="auth-stage-copy">
                                    <strong>Kehadiran</strong>
                                    <small>Check in dan check out</small>
                                </div>
                            </div>

                            <span class="auth-stage-arrow" aria-hidden="true">&rarr;</span>

                            <div class="auth-stage-chip">
                                <span class="auth-stage-number">3</span>
                                <div class="auth-stage-copy">
                                    <strong>Logbook</strong>
                                    <small>Catatan kegiatan harian</small>
                                </div>
                            </div>

                            <span class="auth-stage-arrow" aria-hidden="true">&rarr;</span>

                            <div class="auth-stage-chip">
                                <span class="auth-stage-number">4</span>
                                <div class="auth-stage-copy">
                                    <strong>Monitoring</strong>
                                    <small>Dipantau mentor dan admin</small>
                                </div>
                            </div>
                        </div>

                        <div class="auth-stage-note">
                            <span class="auth-stage-note-dot"></span>
                            Tahap aktif: Kehadiran
                        </div>
                    </div>
                </div>
            </div>

            <div class="auth-form-panel">
                <div class="auth-form-card">
                    <div class="auth-form-header">
                        <h3>Masuk ke IMS</h3>
                        <p class="panel-subtitle">Masuk dengan email atau NIM/NIS terdaftar.</p>
                    </div>

                    @if ($errors->any())
                        <div class="auth-alert auth-alert-error" role="alert" aria-live="assertive" data-auth-error-summary>
                            <strong>Login belum berhasil.</strong>
                            <span>Periksa kembali data yang Anda masukkan.</span>
                        </div>
                    @endif

                    <form action="{{ route('login.attempt') }}" method="POST" class="auth-form" data-auth-form>
                        @csrf

                        <div class="form-group">
                            <label for="login">Email / NIM / NIS</label>
                            <div class="input-shell {{ $errors->has('login') ? 'has-error' : '' }}">
                                <input
                                    id="login"
                                    type="text"
                                    name="login"
                                    value="{{ old('login') }}"
                                    placeholder="nama@ims.co.id atau 220011"
                                    autocomplete="username"
                                    required
                                    autofocus
                                    aria-invalid="{{ $errors->has('login') ? 'true' : 'false' }}"
                                    @if ($errors->has('login')) aria-describedby="login-error" @endif>
                            </div>
                            @error('login')
                                <small id="login-error" class="form-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-group-head">
                                <label for="password">Password</label>
                                <a href="{{ route('password.request') }}" class="text-link">Forgot password?</a>
                            </div>
                            <div class="input-shell {{ $errors->has('password') ? 'has-error' : '' }}">
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    placeholder="Masukkan password"
                                    autocomplete="current-password"
                                    required
                                    aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                                    @if ($errors->has('password')) aria-describedby="password-error" @endif>
                                <button
                                    type="button"
                                    class="input-action"
                                    data-password-toggle
                                    data-password-target="password"
                                    aria-controls="password"
                                    aria-label="Tampilkan password"
                                    aria-pressed="false">
                                    Show
                                </button>
                            </div>
                            @error('password')
                                <small id="password-error" class="form-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="auth-form-meta">
                            <label class="checkbox" for="remember">
                                <input id="remember" type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                                <span>Remember me</span>
                            </label>
                        </div>

                        <button type="submit" class="button auth-submit-button" data-submit-button data-loading-text="Signing in...">
                            <span class="button-spinner" aria-hidden="true"></span>
                            <span class="button-label">Sign in</span>
                        </button>

                        <div class="auth-security-note" role="status" aria-live="polite">
                            <span class="security-indicator-dot"></span>
                            Secure login
                        </div>
                    </form>

                    <p class="auth-form-footer">
                        Belum punya akun?
                        <a href="{{ route('intern.register') }}" class="panel-link">Registrasi di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
