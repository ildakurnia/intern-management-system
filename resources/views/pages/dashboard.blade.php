@extends('layouts.app')

@section('title', $pageTitle)
@section('page_heading', $pageTitle)

@section('content')
    <section class="page-intro card-surface">
        <div>
            <p class="eyebrow">{{ $roleLabel }} Area</p>
            <h2>{{ $pageTitle }}</h2>
            <p>{{ $pageDescription }}</p>
        </div>
        <div class="intro-badge">Stage 1</div>
    </section>

    <section class="hero-grid">
        <article class="hero-card">
            <div class="hero-copy">
                <h3>Halo, {{ $user->name }}</h3>
                <p>Anda login sebagai {{ strtolower($roleLabel) }}. Dashboard ini sudah diarahkan ke visual template yang lebih konsisten untuk menjadi pondasi modul intern, attendance, dan allowance.</p>
                <div class="hero-actions">
                    <span class="pill pill-primary">{{ $roleLabel }}</span>
                    <span class="pill">Authenticated</span>
                </div>
            </div>

            <div class="hero-visual">
                <img src="{{ asset('assets/img/illustrations/illustration-john-light.png') }}" alt="Dashboard Illustration">
            </div>
        </article>

        <article class="side-card">
            <h3>Progress Saat Ini</h3>
            <ul class="progress-list">
                <li>Login & logout berjalan</li>
                <li>Redirect berdasarkan role aktif</li>
                <li>Role middleware siap dipakai untuk modul berikutnya</li>
            </ul>
        </article>
    </section>

    <section class="card-grid">
        @foreach ($summaryCards as $card)
            <article class="stat-card">
                <div class="stat-icon"></div>
                <p>{{ $card['label'] }}</p>
                <h3>{{ $card['value'] }}</h3>
                <span>{{ $card['hint'] }}</span>
            </article>
        @endforeach
    </section>
@endsection
