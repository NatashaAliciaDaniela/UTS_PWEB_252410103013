@extends('layouts.app')

@section('title', 'Pengelolaan Mahasiswa')

@section('content')
<div class="container page-container">
    <div class="page-heading d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-3">
        <div>
            <span class="eyebrow">DATA AKADEMIK</span>
            <h1 class="section-title mt-1 mb-1">Pengelolaan Mahasiswa</h1>
            <p class="text-muted small mb-0">Daftar informasi mahasiswa untuk keperluan simulasi akademik.</p>
        </div>
        <span class="read-only-label"><i class="bi bi-info-circle me-1"></i> Data simulasi · hanya-baca</span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <article class="mini-stat"><span class="mini-stat-icon stat-blue"><i class="bi bi-people-fill"></i></span><div><span class="stat-label">Total Mahasiswa</span><strong>{{ $statistics['totalMahasiswa'] }}</strong></div></article>
        </div>
        <div class="col-12 col-sm-4">
            <article class="mini-stat"><span class="mini-stat-icon stat-purple"><i class="bi bi-diagram-3-fill"></i></span><div><span class="stat-label">Program Studi</span><strong>{{ $statistics['totalProdi'] }}</strong></div></article>
        </div>
        <div class="col-12 col-sm-4">
            <article class="mini-stat"><span class="mini-stat-icon stat-green"><i class="bi bi-person-check-fill"></i></span><div><span class="stat-label">Mahasiswa Aktif</span><strong>{{ $statistics['mahasiswaAktif'] }}</strong></div></article>
        </div>
    </div>

    <section class="content-card">
        <div class="card-heading">
            <div><h2 class="card-title">Daftar Mahasiswa <span class="record-count ms-1">{{ count($mahasiswa) }} data</span></h2><p class="card-subtitle">Informasi mahasiswa terdaftar.</p></div>
            <span class="record-count">{{ count($mahasiswa) }} data</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle student-table mb-0">
                <thead><tr><th>No.</th><th>NAMA MAHASISWA</th><th>NIM</th><th>PROGRAM STUDI</th><th>SEMESTER</th><th>STATUS</th></tr></thead>
                <tbody>
                    @forelse ($mahasiswa as $student)
                        <tr>
                            <td class="text-muted">{{ $loop->iteration }}</td>
                            <td><span class="student-avatar">{{ mb_strtoupper(mb_substr($student['nama'], 0, 1)) }}</span><span class="fw-semibold">{{ $student['nama'] }}</span></td>
                            <td class="text-muted">{{ $student['nim'] }}</td>
                            <td>{{ $student['prodi'] }}</td>
                            <td>{{ $student['semester'] }}</td>
                            <td><span class="status-badge {{ $student['status'] === 'Aktif' ? 'status-active' : 'status-leave' }}">{{ $student['status'] }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data mahasiswa.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="table-footer">
            <span>Menampilkan {{ count($mahasiswa) }} dari {{ $statistics['totalMahasiswa'] }} mahasiswa</span>
            <span><i class="bi bi-shield-check me-1"></i> Data tidak dapat diubah</span>
        </div>
    </section>
    <p class="data-note mt-3 mb-0"><i class="bi bi-info-circle me-1"></i> Data ditampilkan untuk simulasi akademik. Tidak tersedia penambahan, perubahan, atau penghapusan data.</p>
</div>
@endsection
