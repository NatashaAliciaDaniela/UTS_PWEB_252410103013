@extends('layouts.app')

@section('title', 'Profil Pengurus | GPdI Dadapan')
@section('page-title', 'Profil Pengurus')

@section('content')
<div class="page-container">
    <div class="page-intro"><div><p class="eyebrow">AKUN ANDA</p><h2>Profil Pengurus</h2><p>Informasi akun yang sedang digunakan di GPdI Dadapan.</p></div><span class="status-pill status-good"><i class="bi bi-mortarboard me-1"></i>Akun simulasi pengurus</span></div>
    <section class="profile-layout">
        <aside class="profile-card">
            <p class="eyebrow">PENGURUS GPdI DADAPAN</p>
            <span class="profile-avatar">{{ mb_strtoupper(mb_substr($username, 0, 1)) }}</span>
            <h3>{{ $username }}</h3>
            <p>Akun pengurus simulasi</p>
            <span class="status-pill status-good"><i class="bi bi-check-circle me-1"></i>Aktif</span>
        </aside>
        <section class="content-card profile-details">
            <div class="card-heading"><div><h2>Informasi akun</h2><p class="mb-0 muted small">Identitas sesi yang sedang digunakan.</p></div><i class="bi bi-person-vcard profile-details-icon"></i></div>
            <div class="profile-fields">
                <div><small>Username</small><strong><i class="bi bi-person me-2"></i>{{ $username }}</strong></div>
                <div><small>Peran</small><strong><i class="bi bi-person-badge me-2"></i>Pengurus gereja</strong></div>
                <div><small>Organisasi</small><strong><i class="bi bi-house-heart me-2"></i>GPdI Dadapan</strong></div>
                <div><small>Status akun</small><strong><i class="bi bi-shield-check me-2"></i>Aktif · Simulasi</strong></div>
            </div>
            <div class="profile-note"><i class="bi bi-info-circle me-2"></i>Username diteruskan melalui parameter URL. Aplikasi tidak membuat sesi autentikasi.</div>
            <a class="btn btn-primary btn-sm" href="{{ route('dashboard', ['username' => $username]) }}"><i class="bi bi-arrow-left me-1"></i>Kembali ke dashboard</a>
        </section>
    </section>
    <div class="profile-callout mt-3"><i class="bi bi-heart"></i><div><strong>Melayani dengan kasih</strong><span>Informasi pada aplikasi ini bersifat simulasi untuk tugas UTS.</span></div></div>
</div>
@endsection
