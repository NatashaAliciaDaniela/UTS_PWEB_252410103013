@extends('layouts.app')

@section('title', 'Kehadiran | GPdI Dadapan')
@section('page-title', 'Kehadiran Ibadah')

@section('content')
<div class="page-container">
    <div class="page-intro"><div><p class="eyebrow">ABSENSI JEMAAT</p><h2>Kehadiran Ibadah Minggu</h2><p>Rekap kehadiran ibadah tanggal {{ $tanggalIbadah }}.</p></div></div>
    @if ($saved)<div class="alert alert-success py-2">Absensi disimpan sementara dan akan tersedia selama server ini berjalan.</div>@endif
    @if ($errors->any())<div class="alert alert-danger py-2">{{ $errors->first() }}</div>@endif
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4"><div class="mini-summary"><span class="summary-icon present"><i class="bi bi-check-lg"></i></span><div><small>Hadir</small><strong id="presentCount">{{ $summary['hadir'] }}</strong></div></div></div>
        <div class="col-12 col-sm-4"><div class="mini-summary"><span class="summary-icon absent"><i class="bi bi-dash-lg"></i></span><div><small>Tidak hadir</small><strong id="absentCount">{{ $summary['tidakHadir'] }}</strong></div></div></div>
        <div class="col-12 col-sm-4"><div class="mini-summary"><span class="summary-icon rate"><i class="bi bi-graph-up"></i></span><div><small>Persentase hadir</small><strong id="attendanceRate">{{ $summary['persentase'] }}%</strong></div></div></div>
    </div>
    <section class="content-card">
        <div class="table-tools"><div class="date-filter"><label for="serviceDate" class="form-label mb-0">Tanggal ibadah</label><input id="serviceDate" class="form-control" type="date" value="2026-11-15"></div><select id="attendanceGroup" class="form-select" aria-label="Filter kelompok"><option value="">Semua kelompok</option><option>Jemaat Umum</option><option>Remaja</option><option>Pemuda</option></select></div>
        <form action="{{ route('kehadiran.store', ['username' => $username]) }}" method="POST">@csrf
        <div class="table-responsive"><table class="table data-table align-middle mb-0"><thead><tr><th>No.</th><th>Nama Jemaat</th><th>Kelompok</th><th>Status Kehadiran</th></tr></thead><tbody>
            @foreach ($kehadiran as $row)
                <tr data-group="{{ $row['kelompok'] }}" data-present="{{ $row['hadir'] ? '1' : '0' }}"><td class="muted">{{ $loop->iteration }}</td><td class="fw-semibold">{{ $row['nama'] }}</td><td><span class="soft-badge">{{ $row['kelompok'] }}</span></td><td><select name="kehadiran[{{ $row['nama'] }}]" class="form-select attendance-status {{ $row['hadir'] ? 'text-success' : 'text-danger' }}" aria-label="Status kehadiran {{ $row['nama'] }}"><option value="1" {{ $row['hadir'] ? 'selected' : '' }}>Hadir</option><option value="0" {{ ! $row['hadir'] ? 'selected' : '' }}>Tidak Hadir</option></select></td></tr>
            @endforeach
        </tbody></table></div>
        <div class="table-foot d-flex justify-content-between align-items-center gap-2"><span>Status disimpan sementara di memori server, bukan database.</span><button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-check2-circle me-1"></i>Simpan absensi</button></div>
        </form>
    </section>
</div>
@push('scripts')
<script>
    const attendanceRows = [...document.querySelectorAll('tr[data-present]')];
    const attendanceGroup = document.querySelector('#attendanceGroup');
    const updateAttendance = () => {
        let present = 0;
        let absent = 0;
        attendanceRows.forEach((row) => {
            const isPresent = row.querySelector('.attendance-status').value === '1';
            row.dataset.present = isPresent ? '1' : '0';
            row.querySelector('.attendance-status').className = `form-select attendance-status ${isPresent ? 'text-success' : 'text-danger'}`;
            present += Number(isPresent);
            absent += Number(!isPresent);
        });
        document.querySelector('#presentCount').textContent = present;
        document.querySelector('#absentCount').textContent = absent;
        document.querySelector('#attendanceRate').textContent = `${Math.round(present / attendanceRows.length * 100)}%`;
        attendanceRows.forEach((row) => { row.hidden = Boolean(attendanceGroup.value) && row.dataset.group !== attendanceGroup.value; });
    };
    document.querySelectorAll('.attendance-status').forEach((select) => select.addEventListener('change', updateAttendance));
    attendanceGroup.addEventListener('change', updateAttendance);
</script>
@endpush
@endsection
