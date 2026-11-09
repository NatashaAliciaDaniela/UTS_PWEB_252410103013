@extends('layouts.app')

@section('title', 'Data Jemaat | GPdI Dadapan')
@section('page-title', 'Data Jemaat')

@section('content')
<div class="page-container">
    <div class="page-intro"><div><p class="eyebrow">ADMINISTRASI</p><h2>Data Jemaat</h2><p>Daftar jemaat GPdI Dadapan dan kelompok pelayanan.</p></div><button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#demoModal"><i class="bi bi-person-plus me-1"></i>Tambah jemaat</button></div>
    @if ($saved)<div class="alert alert-success py-2">Jemaat ditambahkan sementara. Data akan tersedia selama server ini berjalan.</div>@endif
    @if ($errors->any())<div class="alert alert-danger py-2">{{ $errors->first() }}</div>@endif
    <div class="info-banner mb-3"><i class="bi bi-info-circle"></i><span>Data yang ditambahkan tersimpan sementara di server tanpa database dan hilang saat server dihentikan atau dijalankan ulang.</span></div>
    <section class="content-card">
        <div class="table-tools">
            <div class="search-box"><i class="bi bi-search"></i><input id="memberSearch" type="search" class="form-control" placeholder="Cari nama atau nomor telepon" aria-label="Cari jemaat"></div>
            <select id="groupFilter" class="form-select" aria-label="Filter kelompok"><option value="">Semua kelompok</option><option>Jemaat Umum</option><option>Remaja</option><option>Pemuda</option></select>
        </div>
        <div class="table-responsive">
            <table class="table data-table align-middle mb-0" id="membersTable">
                <thead><tr><th>No.</th><th>Nama Jemaat</th><th>Jenis Kelamin</th><th>Kelompok</th><th>Nomor Telepon</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach ($jemaat as $member)
                        <tr data-group="{{ $member['kelompok'] }}" data-search="{{ strtolower($member['nama'].' '.$member['telepon']) }}">
                            <td class="muted">{{ $loop->iteration }}</td><td class="fw-semibold">{{ $member['nama'] }}</td><td>{{ $member['gender'] }}</td><td><span class="soft-badge">{{ $member['kelompok'] }}</span></td><td>{{ $member['telepon'] }}</td><td><span class="status-pill {{ $member['status'] === 'Aktif' ? 'status-good' : 'status-muted' }}">{{ $member['status'] }}</span></td>
                        </tr>
                    @endforeach
                    <tr id="emptyMembers" hidden><td colspan="6" class="text-center muted py-4">Tidak ada jemaat yang sesuai.</td></tr>
                </tbody>
            </table>
        </div>
        <div class="table-foot">Total {{ count($jemaat) }} data jemaat · Data tambahan hanya berlaku selama server berjalan</div>
    </section>
</div>
<div class="modal fade" id="demoModal" tabindex="-1" aria-labelledby="demoModalLabel" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><form action="{{ route('jemaat.store', ['username' => $username]) }}" method="POST">@csrf<div class="modal-header"><h2 class="modal-title fs-5" id="demoModalLabel">Tambah jemaat sementara</h2><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Tutup"></button></div><div class="modal-body"><div class="mb-3"><label class="form-label" for="memberName">Nama jemaat</label><input id="memberName" name="nama" class="form-control" type="text" maxlength="80" required></div><div class="row g-3"><div class="col-6"><label class="form-label" for="memberGender">Jenis kelamin</label><select id="memberGender" name="gender" class="form-select" required><option value="Laki-laki">Laki-laki</option><option value="Perempuan">Perempuan</option></select></div><div class="col-6"><label class="form-label" for="memberGroup">Kelompok</label><select id="memberGroup" name="kelompok" class="form-select" required><option>Jemaat Umum</option><option>Remaja</option><option>Pemuda</option></select></div></div><div class="mt-3"><label class="form-label" for="memberPhone">Nomor telepon</label><input id="memberPhone" name="telepon" class="form-control" type="tel" maxlength="20" required></div><p class="form-note">Data akan berada di memori sementara server, bukan database.</p></div><div class="modal-footer"><button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary" type="submit">Tambah sementara</button></div></form></div></div></div>
@push('scripts')
<script>
    const memberSearch = document.querySelector('#memberSearch');
    const groupFilter = document.querySelector('#groupFilter');
    const memberRows = [...document.querySelectorAll('#membersTable tbody tr[data-group]')];
    const emptyMembers = document.querySelector('#emptyMembers');
    const filterMembers = () => {
        let visible = 0;
        memberRows.forEach((row) => {
            const matches = row.dataset.search.includes(memberSearch.value.toLowerCase()) && (!groupFilter.value || row.dataset.group === groupFilter.value);
            row.hidden = !matches;
            visible += Number(matches);
        });
        emptyMembers.hidden = visible > 0;
    };
    memberSearch.addEventListener('input', filterMembers);
    groupFilter.addEventListener('change', filterMembers);
</script>
@endpush
@endsection
