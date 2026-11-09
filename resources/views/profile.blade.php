@extends('layouts.app')

@section('title', 'Profil Pengurus | GPdI Dadapan')
@section('page-title', 'Profil Pengurus')

@section('content')
<div class="page-container">
    <div class="page-intro"><div><p class="eyebrow">AKUN AKTIF</p><h2>Profil Pengurus</h2><p>Informasi sesi simulasi administrasi GPdI Dadapan.</p></div></div>
    <section class="content-card profile-panel">
        <span class="profile-avatar">{{ mb_strtoupper(mb_substr($username, 0, 1)) }}</span>
        <div><p class="eyebrow">PENGURUS GEREJA</p><h3>{{ $username }}</h3><span class="status-pill status-good">Aktif · Simulasi</span></div>
    </section>
    <div class="info-banner mt-3"><i class="bi bi-info-circle"></i><span>Username dikirim melalui parameter URL. Tidak ada sesi atau data akun yang disimpan.</span></div>
</div>
@endsection
