<div class="page-container">
    <div class="page-intro"><div><p class="eyebrow">PELAYANAN {{ mb_strtoupper($kelompok) }}</p><h2>Kelompok {{ $kelompok }}</h2><p>Informasi anggota, kegiatan, kehadiran, dan kas kelompok.</p></div><a href="{{ route('kehadiran', ['username' => $username]) }}" class="btn btn-outline-primary"><i class="bi bi-calendar2-check me-1"></i>Rekap kehadiran</a></div>
    @if ($saved)<div class="alert alert-success py-2">Transaksi kas ditambahkan sementara selama server ini berjalan.</div>@endif
    @if ($errors->any())<div class="alert alert-danger py-2">{{ $errors->first() }}</div>@endif
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4"><div class="mini-summary"><span class="summary-icon rate"><i class="bi bi-people"></i></span><div><small>Total anggota</small><strong>{{ count($anggota) }}</strong></div></div></div>
        <div class="col-12 col-sm-4"><div class="mini-summary"><span class="summary-icon present"><i class="bi bi-check-lg"></i></span><div><small>Hadir ibadah terakhir</small><strong>{{ $hadir }}</strong></div></div></div>
        <div class="col-12 col-sm-4"><div class="mini-summary"><span class="summary-icon rate"><i class="bi bi-wallet2"></i></span><div><small>Saldo kas</small><strong class="amount">Rp {{ number_format($saldo, 0, ',', '.') }}</strong></div></div></div>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-12 col-xl-7">
            <section class="content-card h-100">
                <div class="card-heading"><div><p class="eyebrow">ANGGOTA</p><h2>Daftar {{ $kelompok }}</h2></div></div>
                <div class="table-responsive"><table class="table data-table align-middle mb-0"><thead><tr><th>No.</th><th>Nama Jemaat</th><th>Kontak</th><th>Status</th></tr></thead><tbody>
                    @foreach ($anggota as $member)
                        <tr><td class="muted">{{ $loop->iteration }}</td><td class="fw-semibold">{{ $member['nama'] }}</td><td>{{ $member['telepon'] }}</td><td><span class="status-pill {{ $member['status'] === 'Aktif' ? 'status-good' : 'status-muted' }}">{{ $member['status'] }}</span></td></tr>
                    @endforeach
                </tbody></table></div>
            </section>
        </div>
        <div class="col-12 col-xl-5">
            <section class="content-card h-100">
                <div class="card-heading"><div><p class="eyebrow">KEGIATAN BERIKUTNYA</p><h2>{{ $kegiatan['nama'] }}</h2></div><i class="bi bi-calendar-heart card-heading-icon"></i></div>
                <div class="event-details"><p><i class="bi bi-calendar3"></i>{{ $kegiatan['tanggal'] }}</p><p><i class="bi bi-clock"></i>{{ $kegiatan['waktu'] }}</p><p><i class="bi bi-geo-alt"></i>{{ $kegiatan['lokasi'] }}</p><a class="text-link" href="{{ route('ibadah', ['username' => $username]) }}">Jadwal kegiatan <i class="bi bi-arrow-right"></i></a></div>
                <div class="qris-placeholder"><img src="{{ asset('images/qris-placeholder.svg') }}" alt="Placeholder: QRIS belum tersedia"><div><strong>QRIS belum tersedia</strong><span>Belum ada kode pembayaran resmi. Placeholder ini tidak dapat digunakan untuk transaksi.</span></div></div>
            </section>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-12 col-xl-7">
            <section class="content-card">
                <div class="card-heading"><div><p class="eyebrow">KEUANGAN KELOMPOK</p><h2>Riwayat Kas {{ $kelompok }}</h2></div><strong class="amount">Saldo Rp {{ number_format($saldo, 0, ',', '.') }}</strong></div>
                <div class="table-responsive"><table class="table data-table align-middle mb-0"><thead><tr><th>Tanggal</th><th>Keterangan</th><th>Jenis</th><th class="text-end">Nominal</th></tr></thead><tbody>
                    @foreach ($transaksi as $entry)
                        <tr><td>{{ $entry['tanggal'] }}</td><td>{{ $entry['keterangan'] }}</td><td><span class="status-pill {{ $entry['jenis'] === 'Pemasukan' ? 'status-good' : 'status-muted' }}">{{ $entry['jenis'] }}</span></td><td class="text-end amount {{ $entry['jenis'] === 'Pemasukan' ? 'text-success' : 'text-danger' }}">Rp {{ number_format($entry['nominal'], 0, ',', '.') }}</td></tr>
                    @endforeach
                </tbody></table></div>
            </section>
        </div>
        <div class="col-12 col-xl-5">
            <section class="content-card form-card">
                <div class="card-heading"><div><p class="eyebrow">SIMULASI</p><h2>Catat transaksi kas</h2></div></div>
                <form class="p-3" action="{{ route('group-cash.store', ['group' => strtolower($kelompok), 'username' => $username]) }}" method="POST">
                    @csrf
                    <div class="mb-3"><label class="form-label">Tanggal</label><input name="tanggal" class="form-control" type="date" required></div>
                    <div class="mb-3"><label class="form-label">Keterangan</label><input name="keterangan" class="form-control" type="text" placeholder="Contoh: Iuran bulanan" maxlength="120" required></div>
                    <div class="row g-2 mb-3"><div class="col-6"><label class="form-label">Jenis transaksi</label><select name="jenis" class="form-select"><option>Pemasukan</option><option>Pengeluaran</option></select></div><div class="col-6"><label class="form-label">Nominal (Rp)</label><input name="nominal" class="form-control" type="number" min="1" required></div></div>
                    <button class="btn btn-primary w-100" type="submit">Tambah transaksi sementara</button>
                    <p class="form-note">Disimpan di memori server; hilang saat server dihentikan.</p>
                </form>
            </section>
        </div>
    </div>
</div>
