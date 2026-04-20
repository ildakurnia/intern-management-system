@extends('layouts.app')

@section('title', 'Login IMS')
@section('page_heading', 'Login')

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
                    <p class="eyebrow">IMS Portal Access</p>
                    <h2>Masuk ke dashboard sesuai role Anda</h2>
                    <p>UI login ini direvisi mengikuti arah visual template yang sudah ada di project: split cover, illustration panel, dan card form yang lebih konsisten.</p>
                </div>

                <div class="auth-feature-list">
                    <div class="feature-item">
                        <span class="feature-badge feature-badge-primary">01</span>
                        <div>
                            <h3>Role Redirect</h3>
                            <p>Admin, mentor, dan intern masuk ke dashboard masing-masing secara otomatis.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <span class="feature-badge feature-badge-success">02</span>
                        <div>
                            <h3>Clean Service Layer</h3>
                            <p>Controller tetap tipis karena proses login dipindahkan ke service.</p>
                        </div>
                    </div>
                </div>

                <img src="{{ asset('assets/img/illustrations/auth-login-illustration-light.png') }}" alt="Login Illustration">
            </div>

            <div class="auth-form-panel">
                <div class="panel-card">
                    <h3>Sign in</h3>
                    <p class="panel-subtitle">Intern dapat masuk memakai email atau NIM/NIS setelah registrasi.</p>

                    <form action="{{ route('login.attempt') }}" method="POST" class="auth-form">
                        @csrf

                        <div class="form-group">
                            <label for="login">Email atau NIM/NIS</label>
                            <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus>
                            @error('login')
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

                        <label class="checkbox">
                            <input type="checkbox" name="remember" value="1">
                            <span>Remember me</span>
                        </label>

                        <button type="submit" class="button">Login</button>
                    </form>

                    <p class="panel-subtitle">
                        Belum punya akun intern?
                        <a href="{{ route('intern.register') }}" style="color: var(--primary); font-weight: 700;">Registrasi di sini</a>
                    </p>
                </div>

                <div class="login-help">
                    <h3>Akun review</h3>
                    <ul>
                        <li>superadmin@ims.test / password</li>
                        <li>admin@ims.test / password</li>
                        <li>mentor@ims.test / password</li>
                        <li>intern@ims.test / password</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
