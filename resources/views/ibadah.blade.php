@extends('layouts.app')

@section('title', 'Informasi Ibadah | GPdI Dadapan')
@section('page-title', 'Informasi Ibadah')

@section('content')
<div class="page-container">
    <div class="page-intro"><div><p class="eyebrow">JADWAL PELAYANAN</p><h2>Informasi Ibadah</h2><p>Jadwal dan informasi kegiatan gereja yang akan datang.</p></div><button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#serviceForm" aria-expanded="false" aria-controls="serviceForm"><i class="bi bi-plus-lg me-1"></i>Tambah kegiatan</button></div>
    @if ($saved)<div class="alert alert-success py-2">Kegiatan ditambahkan sementara selama server ini berjalan.</div>@endif
    @if ($errors->any())<div class="alert alert-danger py-2">{{ $errors->first() }}</div>@endif
    <div class="info-banner mb-3"><i class="bi bi-info-circle"></i><span>Data kegiatan tambahan hanya berada di memori server dan hilang saat server dihentikan atau dijalankan ulang.</span></div>
    <section class="content-card collapse mb-4" id="serviceForm"><div class="card-heading"><div><p class="eyebrow">SIMULASI</p><h2>Form informasi ibadah</h2></div></div>
        <form class="p-3" action="{{ route('ibadah.store', ['username' => $username]) }}" method="POST">@csrf<div class="row g-3"><div class="col-12 col-md-6"><label class="form-label">Nama kegiatan</label><input name="nama" class="form-control" type="text" maxlength="100" required></div><div class="col-12 col-md-3"><label class="form-label">Tanggal</label><input name="tanggal" class="form-control" type="date" required></div><div class="col-12 col-md-3"><label class="form-label">Waktu</label><input name="waktu" class="form-control" type="time" required></div><div class="col-12 col-md-6"><label class="form-label">Tema</label><input name="tema" class="form-control" type="text" maxlength="120" required></div><div class="col-12 col-md-6"><label class="form-label">Pembicara</label><input name="pembicara" class="form-control" type="text" maxlength="100"></div><div class="col-12"><label class="form-label">Lokasi</label><input name="lokasi" class="form-control" type="text" value="GPdI Dadapan" maxlength="100" required></div><div class="col-12"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="2" maxlength="500"></textarea></div><div class="col-12"><label class="form-label">Preview poster lokal (tidak disimpan)</label><input id="posterFile" class="form-control" type="file" accept="image/*"><img id="posterPreview" class="poster-local-preview mt-3" alt="Preview poster yang dipilih" hidden></div></div><button class="btn btn-primary mt-3" type="submit">Tambah jadwal sementara</button></form>
    </section>
    <div class="row g-3">
        @foreach ($jadwal as $event)
            <div class="col-12 col-lg-6">
                <article class="service-card">
                    <img src="{{ asset('images/poster-ibadah.svg') }}" alt="Ilustrasi poster {{ $event['nama'] }}">
                    <div class="service-card-body"><span class="soft-badge mb-2">{{ $event['tanggal'] }}</span><h3>{{ $event['nama'] }}</h3><p class="service-theme">{{ $event['tema'] }}</p><p><i class="bi bi-clock"></i>{{ $event['waktu'] }}</p><p><i class="bi bi-person"></i>{{ $event['pembicara'] }}</p><p><i class="bi bi-geo-alt"></i>{{ $event['lokasi'] }}</p><p class="muted">{{ $event['deskripsi'] }}</p></div>
                </article>
            </div>
        @endforeach
    </div>
</div>
@push('scripts')
<script>
    const posterFile = document.querySelector('#posterFile');
    const posterPreview = document.querySelector('#posterPreview');
    posterFile.addEventListener('change', () => {
        const [file] = posterFile.files;
        if (!file) {
            posterPreview.hidden = true;
            posterPreview.removeAttribute('src');
            return;
        }
        posterPreview.src = URL.createObjectURL(file);
        posterPreview.hidden = false;
    });
</script>
@endpush
@endsection
