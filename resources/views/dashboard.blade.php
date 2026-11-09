@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container page-container">
    <div class="dashboard-heading mb-4">
        <div>
            <span class="eyebrow">STUDENT INFORMATION DASHBOARD</span>
            <h1 class="section-title">Dashboard</h1>
            <p>Selamat datang, {{ $username }}. Berikut ringkasan informasi akademik.</p>
        </div>
    </div>

    <img
        class="dashboard-campus-photo mb-4"
        src="{{ asset('images/kampus-universitas-jember.png') }}"
        alt="Pemandangan kampus Universitas Jember"
    >

    <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
        <div>
            <span class="eyebrow">RINGKASAN AKADEMIK</span>
            <h2 class="section-title mt-1">Dashboard</h2>
        </div>
        <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i> Tahun akademik berjalan</span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <article class="stat-card h-100">
                <div class="stat-icon stat-blue"><i class="bi bi-people-fill"></i></div>
                <div><p class="stat-label">Total Mahasiswa</p><p class="stat-value">{{ $statistics['totalMahasiswa'] }}</p><p class="stat-caption">Data mahasiswa terdaftar</p></div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <article class="stat-card h-100">
                <div class="stat-icon stat-purple"><i class="bi bi-diagram-3-fill"></i></div>
                <div><p class="stat-label">Program Studi</p><p class="stat-value">{{ $statistics['totalProdi'] }}</p><p class="stat-caption">Pilihan bidang akademik</p></div>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <article class="stat-card h-100">
                <div class="stat-icon stat-green"><i class="bi bi-person-check-fill"></i></div>
                <div><p class="stat-label">Mahasiswa Aktif</p><p class="stat-value">{{ $statistics['mahasiswaAktif'] }}</p><p class="stat-caption">Sedang menempuh studi</p></div>
            </article>
        </div>
    </div>

    <section class="content-card">
        <div class="card-heading">
            <div>
                <h3 class="card-title">Mahasiswa Terbaru</h3>
                <p class="text-muted small mb-0">Informasi mahasiswa terdaftar.</p>
            </div>
            <a href="{{ route('pengelolaan', ['username' => $username]) }}" class="btn btn-outline-primary btn-sm">Lihat semua <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle student-table mb-0">
                <thead><tr><th>Mahasiswa</th><th>NIM</th><th>Program Studi</th><th>Semester</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($mahasiswaTerbaru as $student)
                        <tr>
                            <td><span class="student-avatar">{{ mb_strtoupper(mb_substr($student['nama'], 0, 1)) }}</span><span class="fw-semibold">{{ $student['nama'] }}</span></td>
                            <td class="text-muted">{{ $student['nim'] }}</td>
                            <td>{{ $student['prodi'] }}</td>
                            <td>{{ $student['semester'] }}</td>
                            <td><span class="status-badge {{ $student['status'] === 'Aktif' ? 'status-active' : 'status-leave' }}">{{ $student['status'] }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data mahasiswa.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
