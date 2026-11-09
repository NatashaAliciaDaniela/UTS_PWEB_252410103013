<?php

namespace App\Http\Controllers;

use App\Support\ChurchRuntimeStore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class PageController extends Controller
{
    public function login(): View
    {
        return view('login', ['username' => '', 'errorMessage' => null, 'hideNavigation' => true]);
    }

    public function processLogin(Request $request): RedirectResponse|View
    {
        $rawUsername = $request->input('username');
        $username = is_string($rawUsername) ? trim($rawUsername) : $rawUsername;
        $validator = Validator::make(
            ['username' => $username],
            ['username' => ['required', 'string', 'max:40']]
        );

        if ($validator->fails()) {
            return view('login', [
                'username' => is_string($username) ? $username : '',
                'errorMessage' => $validator->errors()->first('username'),
                'hideNavigation' => true,
            ]);
        }

        return redirect()->route('dashboard', ['username' => $username]);
    }

    public function dashboard(Request $request): View|RedirectResponse
    {
        if (($username = $this->username($request)) === null) {
            return redirect()->route('login');
        }

        $members = $this->members();
        $attendance = $this->attendance();
        $groups = ['Jemaat Umum', 'Remaja', 'Pemuda'];
        $counts = array_count_values(array_column($members, 'kelompok'));

        return view('dashboard', [
            'username' => $username,
            'statistics' => [
                'jemaat' => count($members),
                'remaja' => $counts['Remaja'] ?? 0,
                'pemuda' => $counts['Pemuda'] ?? 0,
                'hadir' => count(array_filter($attendance, fn (array $row): bool => $row['hadir'])),
                'persembahan' => $this->latestOfferingTotal(),
                'kasRemaja' => $this->balance($this->groupTransactions('Remaja')),
                'kasPemuda' => $this->balance($this->groupTransactions('Pemuda')),
            ],
            'ibadah' => $this->upcomingService(),
            'attendanceSummary' => $this->attendanceSummary($attendance),
            'activities' => [
                ['title' => 'Absensi ibadah Minggu diperbarui', 'time' => '15 November 2026 · 08.00 WIB', 'icon' => 'bi-people'],
                ['title' => 'Informasi ibadah berikutnya tersedia', 'time' => '22 November 2026 · 08.00 WIB', 'icon' => 'bi-calendar-event'],
                ['title' => 'Rekap persembahan ibadah dicatat', 'time' => '15 November 2026 · 09.30 WIB', 'icon' => 'bi-heart'],
            ],
        ]);
    }

    public function jemaat(Request $request): View|RedirectResponse
    {
        if (($username = $this->username($request)) === null) {
            return redirect()->route('login');
        }

        return view('jemaat', [
            'username' => $username,
            'jemaat' => $this->members(),
            'saved' => $request->query('saved') === '1',
        ]);
    }

    public function storeJemaat(Request $request): RedirectResponse
    {
        $username = $this->username($request);
        if ($username === null) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:80'],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'kelompok' => ['required', 'in:Jemaat Umum,Remaja,Pemuda'],
            'telepon' => ['required', 'string', 'max:20'],
        ]);
        $validated['status'] = 'Aktif';
        ChurchRuntimeStore::append('members', $validated);

        return redirect()->route('jemaat', ['username' => $username, 'saved' => 1]);
    }

    public function kehadiran(Request $request): View|RedirectResponse
    {
        if (($username = $this->username($request)) === null) {
            return redirect()->route('login');
        }

        $attendance = $this->attendance();

        return view('kehadiran', [
            'username' => $username,
            'kehadiran' => $attendance,
            'summary' => $this->attendanceSummary($attendance),
            'tanggalIbadah' => '15 November 2026',
            'saved' => $request->query('saved') === '1',
        ]);
    }

    public function storeAttendance(Request $request): RedirectResponse
    {
        $username = $this->username($request);
        if ($username === null) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'kehadiran' => ['required', 'array'],
            'kehadiran.*' => ['required', 'in:0,1'],
        ]);
        ChurchRuntimeStore::setAttendance($validated['kehadiran']);

        return redirect()->route('kehadiran', ['username' => $username, 'saved' => 1]);
    }

    public function remaja(Request $request): View|RedirectResponse
    {
        return $this->groupPage($request, 'Remaja');
    }

    public function pemuda(Request $request): View|RedirectResponse
    {
        return $this->groupPage($request, 'Pemuda');
    }

    public function kas(Request $request): View|RedirectResponse
    {
        if (($username = $this->username($request)) === null) {
            return redirect()->route('login');
        }

        $transactions = $this->churchTransactions();
        $income = array_sum(array_column(array_filter($transactions, fn (array $row): bool => $row['jenis'] === 'Pemasukan'), 'nominal'));
        $expense = array_sum(array_column(array_filter($transactions, fn (array $row): bool => $row['jenis'] === 'Pengeluaran'), 'nominal'));

        return view('kas', [
            'username' => $username,
            'transaksi' => $transactions,
            'ringkasan' => ['pemasukan' => $income, 'pengeluaran' => $expense, 'saldo' => $income - $expense],
            'saved' => $request->query('saved') === '1',
        ]);
    }

    public function storeCash(Request $request): RedirectResponse
    {
        $username = $this->username($request);
        if ($username === null) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'kategori' => ['required', 'in:Kas Umum,Kas Remaja,Kas Pemuda'],
            'keterangan' => ['required', 'string', 'max:120'],
            'jenis' => ['required', 'in:Pemasukan,Pengeluaran'],
            'nominal' => ['required', 'integer', 'min:1', 'max:999999999'],
        ]);
        $validated['tanggal'] = $this->formatDate($validated['tanggal']);
        $validated['nominal'] = (int) $validated['nominal'];
        ChurchRuntimeStore::append('church_transactions', $validated);

        if ($validated['kategori'] !== 'Kas Umum') {
            ChurchRuntimeStore::append(
                'group_transactions_'.strtolower(substr($validated['kategori'], 4)),
                [
                    'tanggal' => $validated['tanggal'],
                    'keterangan' => $validated['keterangan'],
                    'jenis' => $validated['jenis'],
                    'nominal' => $validated['nominal'],
                ]
            );
        }

        return redirect()->route('kas', ['username' => $username, 'saved' => 1]);
    }

    public function persembahan(Request $request): View|RedirectResponse
    {
        if (($username = $this->username($request)) === null) {
            return redirect()->route('login');
        }

        return view('persembahan', [
            'username' => $username,
            'persembahan' => $this->offerings(),
            'qrisAvailable' => false,
            'saved' => $request->query('saved') === '1',
        ]);
    }

    public function storeOffering(Request $request): RedirectResponse
    {
        $username = $this->username($request);
        if ($username === null) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'ibadah' => ['required', 'string', 'max:80'],
            'jenis' => ['required', 'in:Persembahan umum,Persembahan khusus,Ucapan syukur,Lainnya'],
            'metode' => ['required', 'in:Tunai,QRIS'],
            'keterangan' => ['required', 'string', 'max:120'],
            'nominal' => ['required', 'integer', 'min:1', 'max:999999999'],
        ]);
        $validated['tanggal'] = $this->formatDate($validated['tanggal']);
        $validated['nominal'] = (int) $validated['nominal'];
        ChurchRuntimeStore::append('offerings', $validated);

        return redirect()->route('persembahan', ['username' => $username, 'saved' => 1]);
    }

    public function ibadah(Request $request): View|RedirectResponse
    {
        if (($username = $this->username($request)) === null) {
            return redirect()->route('login');
        }

        return view('ibadah', [
            'username' => $username,
            'jadwal' => $this->services(),
            'saved' => $request->query('saved') === '1',
        ]);
    }

    public function storeService(Request $request): RedirectResponse
    {
        $username = $this->username($request);
        if ($username === null) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'tanggal' => ['required', 'date'],
            'waktu' => ['required', 'date_format:H:i'],
            'tema' => ['required', 'string', 'max:120'],
            'pembicara' => ['nullable', 'string', 'max:100'],
            'lokasi' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ]);
        $validated['tanggal'] = $this->formatDate($validated['tanggal']);
        $validated['waktu'] .= ' WIB';
        $validated['pembicara'] = $validated['pembicara'] ?: 'Belum ditentukan';
        $validated['deskripsi'] = $validated['deskripsi'] ?: 'Informasi kegiatan simulasi GPdI Dadapan.';
        ChurchRuntimeStore::append('services', $validated);

        return redirect()->route('ibadah', ['username' => $username, 'saved' => 1]);
    }

    public function profile(Request $request): View|RedirectResponse
    {
        if (($username = $this->username($request)) === null) {
            return redirect()->route('login');
        }

        return view('profile', ['username' => $username]);
    }

    public function storeGroupTransaction(Request $request, string $group): RedirectResponse
    {
        $username = $this->username($request);
        if ($username === null) {
            return redirect()->route('login');
        }

        $groupName = ucfirst(strtolower($group));
        if (! in_array($groupName, ['Remaja', 'Pemuda'], true)) {
            abort(404);
        }

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'keterangan' => ['required', 'string', 'max:120'],
            'jenis' => ['required', 'in:Pemasukan,Pengeluaran'],
            'nominal' => ['required', 'integer', 'min:1', 'max:999999999'],
        ]);
        $validated['tanggal'] = $this->formatDate($validated['tanggal']);
        $validated['nominal'] = (int) $validated['nominal'];
        ChurchRuntimeStore::append('group_transactions_'.strtolower($groupName), $validated);
        ChurchRuntimeStore::append('church_transactions', $validated + ['kategori' => 'Kas '.$groupName]);

        return redirect()->route(strtolower($groupName), ['username' => $username, 'saved' => 1]);
    }

    private function groupPage(Request $request, string $group): View|RedirectResponse
    {
        if (($username = $this->username($request)) === null) {
            return redirect()->route('login');
        }

        $members = array_values(array_filter(
            $this->members(),
            fn (array $member): bool => $member['kelompok'] === $group
        ));
        $transactions = $this->groupTransactions($group);

        return view(strtolower($group), [
            'username' => $username,
            'kelompok' => $group,
            'anggota' => $members,
            'transaksi' => $transactions,
            'saldo' => $this->balance($transactions),
            'hadir' => count(array_filter(
                $this->attendance(),
                fn (array $row): bool => $row['kelompok'] === $group && $row['hadir']
            )),
            'kegiatan' => $group === 'Remaja'
                ? ['nama' => 'Persekutuan Remaja', 'tanggal' => 'Sabtu, 21 November 2026', 'waktu' => '16.00 WIB', 'lokasi' => 'GPdI Dadapan']
                : ['nama' => 'Ibadah Pemuda', 'tanggal' => 'Sabtu, 28 November 2026', 'waktu' => '18.00 WIB', 'lokasi' => 'GPdI Dadapan'],
            'qrisAvailable' => false,
            'saved' => $request->query('saved') === '1',
        ]);
    }

    private function username(Request $request): ?string
    {
        $value = $request->query('username');
        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);
        $validator = Validator::make(
            ['username' => $value],
            ['username' => ['required', 'string', 'max:40']]
        );

        return $validator->fails() ? null : $value;
    }

    private function members(): array
    {
        return array_merge([
            ['nama' => 'Andreas Pratama', 'gender' => 'Laki-laki', 'kelompok' => 'Jemaat Umum', 'telepon' => '0812-3456-7801', 'status' => 'Aktif'],
            ['nama' => 'Maria Natalia', 'gender' => 'Perempuan', 'kelompok' => 'Jemaat Umum', 'telepon' => '0812-3456-7802', 'status' => 'Aktif'],
            ['nama' => 'Daniel Setiawan', 'gender' => 'Laki-laki', 'kelompok' => 'Remaja', 'telepon' => '0812-3456-7803', 'status' => 'Aktif'],
            ['nama' => 'Debora Lestari', 'gender' => 'Perempuan', 'kelompok' => 'Remaja', 'telepon' => '0812-3456-7804', 'status' => 'Aktif'],
            ['nama' => 'Yohanes Saputra', 'gender' => 'Laki-laki', 'kelompok' => 'Pemuda', 'telepon' => '0812-3456-7805', 'status' => 'Aktif'],
            ['nama' => 'Hana Putri', 'gender' => 'Perempuan', 'kelompok' => 'Pemuda', 'telepon' => '0812-3456-7806', 'status' => 'Aktif'],
            ['nama' => 'Samuel Wijaya', 'gender' => 'Laki-laki', 'kelompok' => 'Jemaat Umum', 'telepon' => '0812-3456-7807', 'status' => 'Aktif'],
            ['nama' => 'Ruth Amelia', 'gender' => 'Perempuan', 'kelompok' => 'Remaja', 'telepon' => '0812-3456-7808', 'status' => 'Aktif'],
            ['nama' => 'Mikhael Gunawan', 'gender' => 'Laki-laki', 'kelompok' => 'Pemuda', 'telepon' => '0812-3456-7809', 'status' => 'Aktif'],
            ['nama' => 'Esther Kurnia', 'gender' => 'Perempuan', 'kelompok' => 'Jemaat Umum', 'telepon' => '0812-3456-7810', 'status' => 'Aktif'],
            ['nama' => 'Timotius Adi', 'gender' => 'Laki-laki', 'kelompok' => 'Remaja', 'telepon' => '0812-3456-7811', 'status' => 'Aktif'],
            ['nama' => 'Lydia Maharani', 'gender' => 'Perempuan', 'kelompok' => 'Pemuda', 'telepon' => '0812-3456-7812', 'status' => 'Aktif'],
            ['nama' => 'Paulus Hendra', 'gender' => 'Laki-laki', 'kelompok' => 'Jemaat Umum', 'telepon' => '0812-3456-7813', 'status' => 'Aktif'],
            ['nama' => 'Kezia Aprilia', 'gender' => 'Perempuan', 'kelompok' => 'Remaja', 'telepon' => '0812-3456-7814', 'status' => 'Tidak Aktif'],
            ['nama' => 'Markus Firmansyah', 'gender' => 'Laki-laki', 'kelompok' => 'Pemuda', 'telepon' => '0812-3456-7815', 'status' => 'Aktif'],
        ], ChurchRuntimeStore::get('members'));
    }

    private function attendance(): array
    {
        $rows = [];
        $overrides = ChurchRuntimeStore::attendance();
        foreach ($this->members() as $index => $member) {
            $rows[] = $member + ['hadir' => (bool) ($overrides[$member['nama']] ?? ($index % 5 !== 3))];
        }

        return $rows;
    }

    private function attendanceSummary(array $attendance): array
    {
        $present = count(array_filter($attendance, fn (array $row): bool => $row['hadir']));
        $total = count($attendance);

        return [
            'hadir' => $present,
            'tidakHadir' => $total - $present,
            'total' => $total,
            'persentase' => $total > 0 ? (int) round($present / $total * 100) : 0,
        ];
    }

    private function upcomingService(): array
    {
        return $this->services()[0];
    }

    private function services(): array
    {
        return array_merge(ChurchRuntimeStore::get('services'), [
            ['nama' => 'Ibadah Minggu', 'tanggal' => '22 November 2026', 'waktu' => '08.00 WIB', 'tema' => 'Hidup dalam Kasih Tuhan', 'pembicara' => 'Pdt. Daniel Santoso', 'lokasi' => 'GPdI Dadapan', 'deskripsi' => 'Mari beribadah dan bertumbuh bersama dalam kasih Tuhan.'],
            ['nama' => 'Persekutuan Remaja', 'tanggal' => '21 November 2026', 'waktu' => '16.00 WIB', 'tema' => 'Bertumbuh dalam Iman', 'pembicara' => 'Tim Pelayanan Remaja', 'lokasi' => 'GPdI Dadapan', 'deskripsi' => 'Persekutuan dan pujian bersama remaja gereja.'],
            ['nama' => 'Ibadah Pemuda', 'tanggal' => '28 November 2026', 'waktu' => '18.00 WIB', 'tema' => 'Menjadi Terang', 'pembicara' => 'Tim Pelayanan Pemuda', 'lokasi' => 'GPdI Dadapan', 'deskripsi' => 'Ibadah dan kebersamaan pemuda GPdI Dadapan.'],
        ]);
    }

    private function groupTransactions(string $group): array
    {
        $seed = $group === 'Remaja'
            ? [
                ['tanggal' => '14 November 2026', 'keterangan' => 'Iuran persekutuan', 'jenis' => 'Pemasukan', 'nominal' => 150000],
                ['tanggal' => '12 November 2026', 'keterangan' => 'Konsumsi kegiatan', 'jenis' => 'Pengeluaran', 'nominal' => 50000],
                ['tanggal' => '07 November 2026', 'keterangan' => 'Persembahan kasih', 'jenis' => 'Pemasukan', 'nominal' => 100000],
            ]
            : [
                ['tanggal' => '14 November 2026', 'keterangan' => 'Iuran pemuda', 'jenis' => 'Pemasukan', 'nominal' => 250000],
                ['tanggal' => '10 November 2026', 'keterangan' => 'Perlengkapan ibadah', 'jenis' => 'Pengeluaran', 'nominal' => 75000],
                ['tanggal' => '01 November 2026', 'keterangan' => 'Iuran bulanan', 'jenis' => 'Pemasukan', 'nominal' => 200000],
            ];

        return array_merge($seed, ChurchRuntimeStore::get('group_transactions_'.strtolower($group)));
    }

    private function balance(array $transactions): int
    {
        $balance = 0;
        foreach ($transactions as $transaction) {
            $balance += $transaction['jenis'] === 'Pemasukan' ? $transaction['nominal'] : -$transaction['nominal'];
        }

        return $balance;
    }

    private function churchTransactions(): array
    {
        return array_merge([
            ['tanggal' => '15 November 2026', 'kategori' => 'Kas Umum', 'keterangan' => 'Persembahan ibadah Minggu', 'jenis' => 'Pemasukan', 'nominal' => 2000000],
            ['tanggal' => '14 November 2026', 'kategori' => 'Kas Remaja', 'keterangan' => 'Iuran persekutuan', 'jenis' => 'Pemasukan', 'nominal' => 150000],
            ['tanggal' => '12 November 2026', 'kategori' => 'Kas Remaja', 'keterangan' => 'Konsumsi kegiatan', 'jenis' => 'Pengeluaran', 'nominal' => 50000],
            ['tanggal' => '14 November 2026', 'kategori' => 'Kas Pemuda', 'keterangan' => 'Iuran pemuda', 'jenis' => 'Pemasukan', 'nominal' => 250000],
            ['tanggal' => '10 November 2026', 'kategori' => 'Kas Pemuda', 'keterangan' => 'Perlengkapan ibadah', 'jenis' => 'Pengeluaran', 'nominal' => 75000],
            ['tanggal' => '08 November 2026', 'kategori' => 'Kas Umum', 'keterangan' => 'Kebutuhan kebersihan', 'jenis' => 'Pengeluaran', 'nominal' => 300000],
        ], ChurchRuntimeStore::get('church_transactions'));
    }

    private function offerings(): array
    {
        return array_merge([
            ['tanggal' => '15 November 2026', 'ibadah' => 'Ibadah Minggu', 'jenis' => 'Persembahan umum', 'metode' => 'Tunai', 'keterangan' => 'Persembahan ibadah Minggu', 'nominal' => 1500000],
            ['tanggal' => '15 November 2026', 'ibadah' => 'Ibadah Minggu', 'jenis' => 'Persembahan khusus', 'metode' => 'QRIS', 'keterangan' => 'Persembahan misi', 'nominal' => 500000],
            ['tanggal' => '08 November 2026', 'ibadah' => 'Ibadah Minggu', 'jenis' => 'Ucapan syukur', 'metode' => 'Tunai', 'keterangan' => 'Persembahan ucapan syukur', 'nominal' => 350000],
            ['tanggal' => '01 November 2026', 'ibadah' => 'Ibadah Minggu', 'jenis' => 'Persembahan umum', 'metode' => 'QRIS', 'keterangan' => 'Persembahan ibadah Minggu', 'nominal' => 1250000],
        ], ChurchRuntimeStore::get('offerings'));
    }

    private function latestOfferingTotal(): int
    {
        $offerings = $this->offerings();
        $latestTimestamp = 0;
        $total = 0;
        foreach ($offerings as $offering) {
            $timestamp = strtotime($offering['tanggal']);
            if ($timestamp > $latestTimestamp) {
                $latestTimestamp = $timestamp;
                $total = (int) $offering['nominal'];
            } elseif ($timestamp === $latestTimestamp) {
                $total += (int) $offering['nominal'];
            }
        }

        return $total;
    }

    private function formatDate(string $date): string
    {
        return date('d F Y', strtotime($date));
    }
}
