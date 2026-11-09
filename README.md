# StudentHub – Student Information Dashboard

StudentHub adalah aplikasi web simulasi informasi mahasiswa untuk tugas UTS Pemrograman Web. Aplikasi menyediakan login sederhana menggunakan username, dashboard akademik, halaman profil, dan daftar mahasiswa berbasis array PHP.

> Aplikasi ini tidak menyimpan akun atau data mahasiswa. Username diteruskan melalui query parameter, sedangkan daftar mahasiswa disediakan oleh controller. Tidak ada database atau fitur CRUD.

## Teknologi

- PHP 8.3+ (Laravel 13; versi Laravel yang digunakan membutuhkan PHP 8.3 atau lebih baru)
- Laravel 13
- Blade
- Bootstrap 5.3 dan Bootstrap Icons melalui CDN
- CSS3 dan JavaScript bawaan Bootstrap
- Array PHP sebagai sumber data simulasi

## Struktur utama

```text
app/
└── Http/Controllers/PageController.php
public/
├── css/style.css
└── images/
    ├── kampus-universitas-jember.png
    └── logo-universitas-jember.png
resources/views/
├── components/
│   ├── brand.blade.php
│   ├── footer.blade.php
│   ├── nav-links.blade.php
│   └── navbar.blade.php
├── layouts/app.blade.php
├── dashboard.blade.php
├── login.blade.php
├── pengelolaan.blade.php
└── profile.blade.php
routes/web.php
```

## Instalasi dan menjalankan aplikasi

Pastikan PHP 8.3+ dan Composer tersedia, lalu dari direktori proyek jalankan:

```bash
composer install
php artisan serve
```

Buka alamat yang ditampilkan Artisan, biasanya `http://127.0.0.1:8000`. Scaffold Laravel menyertakan `.env`; aplikasi tidak membutuhkan konfigurasi koneksi database atau migrasi. Jika menyiapkan proyek dari awal, buat proyek Laravel 13 terlebih dahulu, lalu salin file aplikasi ini ke direktori tersebut.

## Alur aplikasi

1. Buka `/` dan masukkan username tanpa password.
2. Form mengirim username menggunakan HTTP POST ke `/login`.
3. Setelah validasi berhasil, aplikasi mengarahkan pengguna ke `/dashboard?username=Nama`.
4. Navigasi ke profil dan pengelolaan meneruskan username sebagai query parameter.
5. Link kembali ke login mengakhiri simulasi dengan kembali ke halaman awal; tidak ada sesi autentikasi pengguna.

## Arsitektur MVC

- **Model/data:** array mahasiswa disiapkan di `PageController`, tanpa database maupun Eloquent.
- **View:** file Blade di `resources/views` menggunakan layout bersama, komponen navbar/footer, dan data dari controller.
- **Controller:** `PageController` memvalidasi username, menyiapkan statistik dan data, serta memilih view.
- **Routing:** seluruh halaman dan pemrosesan form terdaftar di `routes/web.php` dan diarahkan ke controller.

## Screenshot

Simpan hasil tangkapan layar aplikasi di `docs/screenshots/` dengan nama berikut:

| Halaman | File screenshot |
|---|---|
| Login | `docs/screenshots/login.png` |
| Dashboard | `docs/screenshots/dashboard.png` |
| Profil | `docs/screenshots/profile.png` |
| Pengelolaan mahasiswa | `docs/screenshots/pengelolaan.png` |

Setelah file screenshot ditambahkan, tampilkan di bagian ini dengan:

```markdown
![Halaman login](docs/screenshots/login.png)
![Dashboard](docs/screenshots/dashboard.png)
![Profil](docs/screenshots/profile.png)
![Pengelolaan mahasiswa](docs/screenshots/pengelolaan.png)
```

## Checklist pengujian UTS

- [ ] `GET /` menampilkan form login dan menolak username kosong.
- [ ] `POST /login` mengarahkan username valid ke dashboard melalui query parameter.
- [ ] Dashboard menampilkan sapaan, statistik controller, dan tabel mahasiswa terbaru.
- [ ] Profil menunjukkan username yang sama dari query parameter.
- [ ] Pengelolaan menampilkan sedikitnya delapan baris data dari array dengan Blade `@foreach`.
- [ ] Link navigasi mempertahankan username dan link keluar kembali ke halaman login.
- [ ] Tabel menggunakan `table-responsive`, dan navbar dapat dibuka pada layar kecil.
- [ ] `php artisan route:list` menampilkan lima route aplikasi.
- [ ] Tidak ada database, Eloquent, starter kit, atau operasi CRUD aplikasi.
