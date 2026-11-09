@extends('layouts.app')

@section('title', 'Kas Gereja | GPdI Dadapan')
@section('page-title', 'Kas Gereja')

@section('content')
<div class="page-container">
    <div class="page-intro"><div><p class="eyebrow">KEUANGAN</p><h2>Kas Gereja</h2><p>Ringkasan kas umum, remaja, dan pemuda GPdI Dadapan.</p></div><a href="{{ route('persembahan', ['username' => $username]) }}" class="btn btn-outline-primary"><i class="bi bi-heart me-1"></i>Persembahan</a></div>
    @if ($saved)<div class="alert alert-success py-2">Transaksi kas ditambahkan sementara selama server ini berjalan.</div>@endif
    @if ($errors->any())<div class="alert alert-danger py-2">{{ $errors->first() }}</div>@endif
    <div class="info-banner mb-3"><i class="bi bi-shield-check"></i><span>Data simulasi berada di memori server tanpa database dan hilang saat server dihentikan atau dijalankan ulang.</span></div>
    <div class="row g-3 mb-4">
        @foreach ([['label' => 'Total Pemasukan', 'amount' => $ringkasan['pemasukan'], 'icon' => 'bi-graph-up-arrow', 'tone' => 'green'], ['label' => 'Total Pengeluaran', 'amount' => $ringkasan['pengeluaran'], 'icon' => 'bi-graph-down-arrow', 'tone' => 'gold'], ['label' => 'Saldo Simulasi', 'amount' => $ringkasan['saldo'], 'icon' => 'bi-wallet2', 'tone' => 'blue']] as $item)
            <div class="col-12 col-md-4"><article class="stat-card"><span class="stat-icon tone-{{ $item['tone'] }}"><i class="bi {{ $item['icon'] }}"></i></span><div><p>{{ $item['label'] }}</p><strong class="amount">Rp {{ number_format($item['amount'], 0, ',', '.') }}</strong></div></article></div>
        @endforeach
    </div>
    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <section class="content-card"><div class="card-heading"><div><p class="eyebrow">TRANSAKSI</p><h2>Riwayat Kas</h2></div></div>
                <div class="table-tools"><input id="cashSearch" class="form-control search-input" type="search" placeholder="Cari keterangan" aria-label="Cari transaksi"><select id="cashCategory" class="form-select" aria-label="Filter kategori"><option value="">Semua kategori</option><option>Kas Umum</option><option>Kas Remaja</option><option>Kas Pemuda</option></select></div>
                <div class="table-responsive"><table class="table data-table align-middle mb-0" id="cashTable"><thead><tr><th>Tanggal</th><th>Kategori</th><th>Keterangan</th><th>Jenis</th><th class="text-end">Nominal</th></tr></thead><tbody>
                    @foreach ($transaksi as $entry)
                        <tr data-category="{{ $entry['kategori'] }}" data-search="{{ strtolower($entry['keterangan']) }}"><td>{{ $entry['tanggal'] }}</td><td><span class="soft-badge">{{ $entry['kategori'] }}</span></td><td>{{ $entry['keterangan'] }}</td><td>{{ $entry['jenis'] }}</td><td class="text-end amount">Rp {{ number_format($entry['nominal'], 0, ',', '.') }}</td></tr>
                    @endforeach
                </tbody></table></div>
            </section>
        </div>
        <div class="col-12 col-xl-4">
            <section class="content-card form-card"><div class="card-heading"><div><p class="eyebrow">SIMULASI</p><h2>Input transaksi</h2></div></div>
                <form class="p-3" action="{{ route('kas.store', ['username' => $username]) }}" method="POST">@csrf<div class="mb-3"><label class="form-label">Tanggal</label><input name="tanggal" type="date" class="form-control" required></div><div class="mb-3"><label class="form-label">Kategori</label><select name="kategori" class="form-select"><option>Kas Umum</option><option>Kas Remaja</option><option>Kas Pemuda</option></select></div><div class="mb-3"><label class="form-label">Keterangan</label><input name="keterangan" class="form-control" type="text" maxlength="120" required></div><div class="row g-2 mb-3"><div class="col-6"><label class="form-label">Jenis</label><select name="jenis" class="form-select"><option>Pemasukan</option><option>Pengeluaran</option></select></div><div class="col-6"><label class="form-label">Nominal</label><input name="nominal" class="form-control" type="number" min="1" required></div></div><button class="btn btn-primary w-100" type="submit">Tambah transaksi sementara</button><p class="form-note">Hilang saat proses server dihentikan.</p></form>
            </section>
        </div>
    </div>
</div>
@push('scripts')
<script>
    const cashCategory = document.querySelector('#cashCategory');
    const cashSearch = document.querySelector('#cashSearch');
    const cashRows = [...document.querySelectorAll('#cashTable tbody tr')];
    const filterCash = () => cashRows.forEach((row) => {
        row.hidden = (cashCategory.value && row.dataset.category !== cashCategory.value) || !row.dataset.search.includes(cashSearch.value.toLowerCase());
    });
    cashCategory.addEventListener('change', filterCash);
    cashSearch.addEventListener('input', filterCash);
</script>
@endpush
@endsection
