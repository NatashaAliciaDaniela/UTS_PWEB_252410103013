@extends('layouts.app')

@section('title', 'Masuk | GPdI Dadapan')

@section('content')
<section class="login-page">
    <section class="login-card">
        <div class="login-brand">
            @include('components.brand')
        </div>
        <p class="eyebrow">PORTAL ADMINISTRASI GEREJA</p>
        <h1>Selamat datang</h1>
        <p class="login-description">Masuk untuk melihat informasi jemaat, pelayanan, dan kegiatan GPdI Dadapan.</p>

        @if ($errorMessage)
            <div class="alert alert-danger" role="alert">{{ $errorMessage }}</div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf
            <label for="username" class="form-label">Username pengurus</label>
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input id="username" name="username" type="text" class="form-control" value="{{ $username }}" placeholder="Masukkan nama pengguna" maxlength="40" autocomplete="username" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary w-100 login-submit">Masuk ke sistem <i class="bi bi-arrow-right ms-1"></i></button>
        </form>
        <p class="login-note"><i class="bi bi-info-circle me-1"></i> Login simulasi UTS. Data tidak disimpan secara permanen.</p>
    </section>
    <div class="login-bottom">GPdI Dadapan <span>·</span> Melayani dengan kasih</div>
</section>
@endsection
