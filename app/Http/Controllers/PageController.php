<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class PageController extends Controller
{
    public function login(): View
    {
        return view('login', [
            'username' => '',
            'errorMessage' => null,
            'hideNavigation' => true,
        ]);
    }

    public function processLogin(Request $request): RedirectResponse|View
    {
        $input = $request->input('username');
        $username = is_string($input) ? trim($input) : $input;
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

    public function dashboard(Request $request): RedirectResponse|View
    {
        $username = $this->usernameFromQuery($request);

        if ($username === null) {
            return redirect()->route('login');
        }

        $mahasiswa = $this->mahasiswa();

        return view('dashboard', [
            'username' => $username,
            'statistics' => $this->statistics($mahasiswa),
            'mahasiswaTerbaru' => array_slice($mahasiswa, 0, 5),
        ]);
    }

    public function profile(Request $request): RedirectResponse|View
    {
        $username = $this->usernameFromQuery($request);

        if ($username === null) {
            return redirect()->route('login');
        }

        return view('profile', ['username' => $username]);
    }

    public function pengelolaan(Request $request): RedirectResponse|View
    {
        $username = $this->usernameFromQuery($request);

        if ($username === null) {
            return redirect()->route('login');
        }

        $mahasiswa = $this->mahasiswa();

        return view('pengelolaan', [
            'username' => $username,
            'mahasiswa' => $mahasiswa,
            'statistics' => $this->statistics($mahasiswa),
        ]);
    }

    private function usernameFromQuery(Request $request): ?string
    {
        $value = $request->query('username');

        if (! is_string($value)) {
            return null;
        }

        $username = trim($value);
        $validator = Validator::make(
            ['username' => $username],
            ['username' => ['required', 'string', 'max:40']]
        );

        return $validator->fails() ? null : $username;
    }

    private function statistics(array $mahasiswa): array
    {
        return [
            'totalMahasiswa' => count($mahasiswa),
            'totalProdi' => count(array_unique(array_column($mahasiswa, 'prodi'))),
            'mahasiswaAktif' => count(array_filter(
                $mahasiswa,
                fn (array $student): bool => $student['status'] === 'Aktif'
            )),
        ];
    }

    private function mahasiswa(): array
    {
        return [
            ['nim' => '202401001', 'nama' => 'Andi Pratama', 'prodi' => 'Informatika', 'semester' => 5, 'status' => 'Aktif'],
            ['nim' => '202401002', 'nama' => 'Siti Rahma', 'prodi' => 'Sistem Informasi', 'semester' => 4, 'status' => 'Aktif'],
            ['nim' => '202401003', 'nama' => 'Budi Santoso', 'prodi' => 'Teknik Komputer', 'semester' => 6, 'status' => 'Aktif'],
            ['nim' => '202401004', 'nama' => 'Dinda Maharani', 'prodi' => 'Informatika', 'semester' => 2, 'status' => 'Aktif'],
            ['nim' => '202401005', 'nama' => 'Fajar Ramadhan', 'prodi' => 'Manajemen', 'semester' => 8, 'status' => 'Cuti'],
            ['nim' => '202401006', 'nama' => 'Nadia Putri', 'prodi' => 'Sistem Informasi', 'semester' => 3, 'status' => 'Aktif'],
            ['nim' => '202401007', 'nama' => 'Rizky Firmansyah', 'prodi' => 'Teknik Komputer', 'semester' => 5, 'status' => 'Aktif'],
            ['nim' => '202401008', 'nama' => 'Alya Safitri', 'prodi' => 'Manajemen', 'semester' => 1, 'status' => 'Aktif'],
        ];
    }
}
