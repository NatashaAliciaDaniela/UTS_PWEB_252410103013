@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<section class="login-shell">
    <div class="login-photo-panel">
        <img
            class="login-campus-photo"
            src="{{ asset('images/kampus-universitas-jember.png') }}"
            alt="Kampus Universitas Jember"
        >
    </div>
    <div class="login-panel">
        <div class="login-card">
            <div class="mb-4">@include('components.brand')</div>
            <span class="eyebrow">PORTAL INFORMASI MAHASISWA</span>
            <h2 class="login-title mt-3">Welcome Back</h2>
            <p class="text-muted mb-4">Masuk dengan username untuk melanjutkan ke dashboard StudentHub.</p>

            @if ($errorMessage)
                <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
                    <i class="bi bi-exclamation-circle-fill mt-1"></i>
                    <span>{{ $errorMessage }}</span>
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST">
                @csrf
                <label for="username" class="form-label fw-semibold">Username</label>
                <div class="input-group login-input mb-4">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control" id="username" name="username" value="{{ $username }}" placeholder="Contoh: Rio" maxlength="40" autocomplete="username" required autofocus>
                </div>
                <button type="submit" class="btn btn-primary w-100 login-button">Masuk</button>
            </form>
            <p class="login-hint mt-3 mb-0"><i class="bi bi-info-circle me-1"></i> Login simulasi untuk keperluan UTS. Tidak menggunakan password.</p>
        </div>
    </div>
</section>
@endsection
