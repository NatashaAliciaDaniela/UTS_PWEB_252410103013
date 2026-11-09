@extends('layouts.app')

@section('title', 'Persembahan | GPdI Dadapan')
@section('page-title', 'Persembahan')

@section('content')
<div class="page-container">
    @php($totalOffering = array_sum(array_column($persembahan, 'nominal')))
    <div class="page-intro"><div><p class="eyebrow">KEUANGAN IBADAH</p><h2>Persembahan</h2><p>Rekap persembahan dari kegiatan ibadah.</p></div><div class="offering-total"><small>Total tercatat</small><strong>Rp {{ number_format($totalOffering, 0, ',', '.') }}</strong></div></div>
    @if ($saved)<div class="alert alert-success py-2">Persembahan ditambahkan sementara selama server ini berjalan.</div>@endif
    @if ($errors->any())<div class="alert alert-danger py-2">{{ $errors->first() }}</div>@endif
    <div class="info-banner mb-3"><i class="bi bi-info-circle"></i><span>Catatan disimpan sementara di memori server. QRIS hanya informasi simulasi dan bukan pembayaran.</span></div>
    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <section class="content-card"><div class="card-heading"><div><p class="eyebrow">RIWAYAT</p><h2>Catatan Persembahan</h2></div></div>
                <div class="table-responsive"><table class="table data-table align-middle mb-0"><thead><tr><th>Tanggal</th><th>Jenis Ibadah</th><th>Jenis Persembahan</th><th>Metode</th><th>Keterangan</th><th class="text-end">Nominal</th></tr></thead><tbody>
                    @foreach ($persembahan as $entry)
                        <tr><td>{{ $entry['tanggal'] }}</td><td>{{ $entry['ibadah'] }}</td><td>{{ $entry['jenis'] }}</td><td>{{ $entry['metode'] }}</td><td>{{ $entry['keterangan'] }}</td><td class="text-end amount">Rp {{ number_format($entry['nominal'], 0, ',', '.') }}</td></tr>
                    @endforeach
                </tbody></table></div>
            </section>
            <section class="content-card form-card mt-3"><div class="card-heading"><div><p class="eyebrow">SIMULASI</p><h2>Catat persembahan</h2></div></div>
                <form class="p-3" action="{{ route('persembahan.store', ['username' => $username]) }}" method="POST">@csrf<div class="row g-3"><div class="col-12 col-md-6"><label class="form-label">Tanggal ibadah</label><input name="tanggal" type="date" class="form-control" required></div><div class="col-12 col-md-6"><label class="form-label">Jenis ibadah</label><input name="ibadah" class="form-control" type="text" placeholder="Ibadah Minggu" maxlength="80" required></div><div class="col-12 col-md-6"><label class="form-label">Jenis persembahan</label><select name="jenis" class="form-select"><option>Persembahan umum</option><option>Persembahan khusus</option><option>Ucapan syukur</option><option>Lainnya</option></select></div><div class="col-12 col-md-6"><label class="form-label">Metode</label><select name="metode" class="form-select"><option>Tunai</option><option>QRIS</option></select></div><div class="col-12"><label class="form-label">Keterangan</label><input name="keterangan" class="form-control" maxlength="120" required></div><div class="col-12"><label class="form-label">Nominal (Rp)</label><input name="nominal" type="number" min="1" class="form-control" required></div></div><button class="btn btn-primary mt-3" type="submit">Tambah catatan sementara</button><span class="form-note d-inline-block ms-2">Hilang saat server dihentikan.</span></form>
            </section>
        </div>
        <div class="col-12 col-xl-4">
            <section class="content-card qris-card"><div class="card-heading"><div><p class="eyebrow">PEMBAYARAN</p><h2>Informasi QRIS</h2></div></div><div class="qris-placeholder qris-large"><img src="{{ asset('images/qris-placeholder.svg') }}" alt="Placeholder: QRIS belum tersedia"><div><strong>QRIS belum tersedia</strong><span>Gunakan kode QR resmi dari gereja jika telah disediakan. Gambar ini hanya placeholder, bukan kode pembayaran.</span></div></div></section>
        </div>
    </div>
</div>
@endsection
