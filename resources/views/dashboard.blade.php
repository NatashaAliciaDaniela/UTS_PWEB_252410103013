@extends('layouts.app')

@section('title', 'Dashboard | GPdI Dadapan')
@section('page-title', 'Dashboard')

@section('content')
<div class="page-container">
    <section class="welcome-panel mb-4">
        <div>
            <p class="eyebrow">MINGGU, 15 NOVEMBER 2026</p>
            <h2>Selamat datang, {{ $username }}</h2>
            <p>Kiranya pelayanan hari ini berjalan dengan sukacita. Berikut ringkasan GPdI Dadapan.</p>
            <a href="{{ route('ibadah', ['username' => $username]) }}" class="btn btn-light btn-sm mt-2">Lihat jadwal ibadah <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
        <div class="welcome-mark"><i class="bi bi-heart-fill"></i></div>
    </section>

    <div class="section-heading"><div><p class="eyebrow">RINGKASAN</p><h2>Informasi Gereja</h2></div></div>
    <div class="row g-3 mb-4">
        @foreach ([
            ['label' => 'Total Jemaat', 'value' => $statistics['jemaat'], 'icon' => 'bi-people', 'tone' => 'blue'],
            ['label' => 'Anggota Remaja', 'value' => $statistics['remaja'], 'icon' => 'bi-emoji-smile', 'tone' => 'gold'],
            ['label' => 'Anggota Pemuda', 'value' => $statistics['pemuda'], 'icon' => 'bi-lightning', 'tone' => 'green'],
            ['label' => 'Hadir Minggu Lalu', 'value' => $statistics['hadir'], 'icon' => 'bi-calendar2-check', 'tone' => 'blue'],
            ['label' => 'Persembahan Terakhir', 'value' => 'Rp '.number_format($statistics['persembahan'], 0, ',', '.'), 'icon' => 'bi-heart', 'tone' => 'gold'],
            ['label' => 'Kas Remaja / Pemuda', 'value' => 'Rp '.number_format($statistics['kasRemaja'] + $statistics['kasPemuda'], 0, ',', '.'), 'icon' => 'bi-wallet2', 'tone' => 'green'],
        ] as $stat)
            <div class="col-12 col-sm-6 col-xl-4">
                <article class="stat-card h-100">
                    <span class="stat-icon tone-{{ $stat['tone'] }}"><i class="bi {{ $stat['icon'] }}"></i></span>
                    <div><p>{{ $stat['label'] }}</p><strong>{{ $stat['value'] }}</strong></div>
                </article>
            </div>
        @endforeach
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-xl-7">
            <section class="content-card h-100">
                <div class="card-heading"><div><p class="eyebrow">IBADAH BERIKUTNYA</p><h2>{{ $ibadah['nama'] }}</h2></div><span class="soft-badge"><i class="bi bi-calendar-event me-1"></i>{{ $ibadah['tanggal'] }}</span></div>
                <div class="service-preview">
                    <img src="{{ asset('images/poster-ibadah.svg') }}" alt="Poster ilustrasi Ibadah Minggu">
                    <div>
                        <h3>{{ $ibadah['tema'] }}</h3>
                        <p><i class="bi bi-clock me-2"></i>{{ $ibadah['waktu'] }}</p>
                        <p><i class="bi bi-person me-2"></i>{{ $ibadah['pembicara'] }}</p>
                        <p><i class="bi bi-geo-alt me-2"></i>{{ $ibadah['lokasi'] }}</p>
                        <a href="{{ route('ibadah', ['username' => $username]) }}" class="text-link">Detail jadwal <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-12 col-xl-5">
            <section class="content-card h-100">
                <div class="card-heading"><div><p class="eyebrow">IBADAH TERAKHIR</p><h2>Ringkasan Kehadiran</h2></div><a href="{{ route('kehadiran', ['username' => $username]) }}" class="text-link">Detail <i class="bi bi-arrow-right"></i></a></div>
                <div class="attendance-summary">
                    <div class="attendance-percent">{{ $attendanceSummary['persentase'] }}<small>%</small></div>
                    <div class="attendance-bar"><span style="width: {{ $attendanceSummary['persentase'] }}%"></span></div>
                    <div class="d-flex justify-content-between mt-3"><span><i class="bi bi-circle-fill text-success me-1"></i>Hadir <strong>{{ $attendanceSummary['hadir'] }}</strong></span><span><i class="bi bi-circle-fill text-danger me-1"></i>Tidak hadir <strong>{{ $attendanceSummary['tidakHadir'] }}</strong></span></div>
                    <p class="muted small mt-3 mb-0">Rekap ibadah Minggu, 15 November 2026</p>
                </div>
            </section>
        </div>
    </div>

    <section class="content-card">
        <div class="card-heading"><div><p class="eyebrow">PELAYANAN</p><h2>Aktivitas Administrasi</h2></div></div>
        <div class="activity-list">
            @foreach ($activities as $activity)
                <div class="activity-item"><span class="activity-icon"><i class="bi {{ $activity['icon'] }}"></i></span><div><strong>{{ $activity['title'] }}</strong><small>{{ $activity['time'] }}</small></div></div>
            @endforeach
        </div>
    </section>
</div>
@endsection
