@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<div class="container page-container">
    <div class="page-heading mb-4">
        <span class="eyebrow">AKUN ANDA</span>
        <h1 class="section-title mt-1 mb-1">Profil Pengguna</h1>
        <p class="text-muted mb-0">Informasi akun yang sedang digunakan di StudentHub.</p>
    </div>
    <section class="profile-card">
        <div class="profile-summary">
            <div class="profile-avatar">{{ mb_strtoupper(mb_substr($username, 0, 1)) }}</div>
            <div>
                <span class="eyebrow">PENGGUNA STUDENTHUB</span>
                <h2 class="profile-name">{{ $username }}</h2>
                <p class="text-muted mb-0">Akun simulasi mahasiswa</p>
            </div>
            <span class="status-badge status-active profile-status">Aktif</span>
        </div>
        <div class="profile-fields row g-4">
            <div class="col-12 col-md-6">
                <p class="profile-field-label">Username</p>
                <p class="profile-field-value"><i class="bi bi-person me-2"></i>{{ $username }}</p>
            </div>
            <div class="col-12 col-md-6">
                <p class="profile-field-label">Jenis akun</p>
                <p class="profile-field-value"><i class="bi bi-mortarboard me-2"></i>Mahasiswa</p>
            </div>
            <div class="col-12 col-md-6">
                <p class="profile-field-label">Platform</p>
                <p class="profile-field-value"><i class="bi bi-grid me-2"></i>StudentHub Dashboard</p>
            </div>
            <div class="col-12 col-md-6">
                <p class="profile-field-label">Status akun</p>
                <p class="profile-field-value"><i class="bi bi-shield-check me-2"></i>Aktif</p>
            </div>
        </div>
        <a href="{{ route('dashboard', ['username' => $username]) }}" class="btn btn-primary mt-3"><i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard</a>
    </section>
</div>
@endsection
