# GPdI Dadapan — Sistem Informasi Gereja

Aplikasi web simulasi administrasi GPdI Dadapan untuk tugas UTS Pemrograman Web. Aplikasi menyediakan ringkasan gereja, data jemaat, absensi, pelayanan remaja dan pemuda, kas, persembahan, serta jadwal ibadah.

> Aplikasi tidak memakai database atau autentikasi kompleks. Data tambahan disimpan sementara di file JSON runtime yang berada di luar `public/`, diberi identitas proses server, dan diinisialisasi ulang ketika server dijalankan dengan proses baru. Data kembali ke contoh awal setelah server dihentikan/dijalankan ulang. Penyimpanan ini untuk demonstrasi satu server lokal, bukan untuk data jemaat atau transaksi resmi.

## Teknologi

- PHP 8.3+ dan Laravel 13
- Blade, Laravel Routing, dan Controller
- Bootstrap 5.3 dan Bootstrap Icons melalui CDN
- CSS, JavaScript, serta array PHP untuk data simulasi

## Struktur fitur

```text
app/Http/Controllers/PageController.php
routes/web.php
resources/views/
├── layouts/app.blade.php
├── components/
│   ├── brand.blade.php
│   ├── footer.blade.php
│   ├── group-content.blade.php
│   ├── navbar.blade.php
│   └── sidebar.blade.php
├── dashboard.blade.php
├── ibadah.blade.php
├── jemaat.blade.php
├── kehadiran.blade.php
├── kas.blade.php
├── login.blade.php
├── pemuda.blade.php
├── persembahan.blade.php
├── profile.blade.php
└── remaja.blade.php
public/
├── css/style.css
└── images/
    ├── logo-gereja.png
    ├── poster-ibadah.svg
    └── qris-placeholder.svg
storage/framework/church-runtime.json   # dibuat saat ada data sementara
tests/Feature/ChurchManagementTest.php
```

## Menjalankan aplikasi

Pastikan PHP 8.3+ dan Composer tersedia:

```bash
composer install
php artisan serve
```

Buka alamat lokal yang ditampilkan Artisan, biasanya `http://127.0.0.1:8000`. Tidak perlu mengatur koneksi atau menjalankan migrasi database.

## Alur dan arsitektur MVC

1. Pengurus membuka `/`, memasukkan username, dan mengirim form melalui `POST /login`.
2. Controller memvalidasi username lalu mengarahkan ke `/dashboard?username=...`.
3. Setiap tautan fitur meneruskan username melalui query parameter.
4. `PageController` menyiapkan data array dan statistik untuk view.
5. View Blade menggunakan layout, komponen navigasi, dan perulangan untuk merender data.

Data contoh, saldo, tanggal, dan kehadiran disiapkan untuk demonstrasi. Form jemaat, absensi, kas, persembahan, dan jadwal menerima input melalui POST, memvalidasinya di controller, lalu menambahkan data pada penyimpanan sementara yang terkait proses server. Perubahan terlihat pada halaman terkait selama server yang sama berjalan dan dihapus secara logis saat proses baru memulai aplikasi. Tidak ada SQL database maupun Eloquent. QRIS merupakan placeholder yang tidak dapat dipakai untuk pembayaran; poster merupakan ilustrasi informasi simulasi.

## Halaman

| Route | Kegunaan |
|---|---|
| `/` | Login simulasi |
| `/dashboard?username=Nama` | Ringkasan gereja dan kegiatan |
| `/jemaat?username=Nama` | Daftar, pencarian, dan filter jemaat |
| `/kehadiran?username=Nama` | Rekap dan simulasi status hadir |
| `/remaja?username=Nama` | Anggota dan kas remaja |
| `/pemuda?username=Nama` | Anggota dan kas pemuda |
| `/kas?username=Nama` | Rekap kas gereja |
| `/persembahan?username=Nama` | Catatan persembahan |
| `/ibadah?username=Nama` | Jadwal dan poster ilustrasi |
| `/profile?username=Nama` | Informasi username pengurus |

## Screenshot

<img width="1920" height="1080" alt="Screenshot 2026-11-10 080409" src="https://github.com/user-attachments/assets/c3ebcdf4-5069-418f-a49b-528facbd78d5" />
<img width="1920" height="1080" alt="Screenshot 2026-11-10 080422" src="https://github.com/user-attachments/assets/ed4c94db-deb1-4355-815a-789450388079" />
<img width="1920" height="1080" alt="Screenshot 2026-11-10 080429" src="https://github.com/user-attachments/assets/f26c4dc9-67b6-4f52-a157-7bdc6d1e2d07" />
<img width="1920" height="1080" alt="Screenshot 2026-11-10 080434" src="https://github.com/user-attachments/assets/0f839c16-2255-40ee-a7b9-759c1ac7070c" />
<img width="1920" height="1080" alt="Screenshot 2026-11-10 080434" src="https://github.com/user-attachments/assets/d03bc91d-6f09-48ac-bf47-a3e46f0a8900" />
<img width="1920" height="1080" alt="Screenshot 2026-11-10 080449" src="https://github.com/user-attachments/assets/9652c534-34c4-4eac-b5a3-7799b68b1c14" />
<img width="1920" height="1080" alt="Screenshot 2026-11-10 080455" src="https://github.com/user-attachments/assets/ca1fbcbd-c1ed-4609-9cf3-eec206d30834" />
<img width="1920" height="1080" alt="Screenshot 2026-11-10 080501" src="https://github.com/user-attachments/assets/a5b9e0e3-1a92-4431-a5b6-bb3258d36a53" />
<img width="1920" height="1080" alt="Screenshot 2026-11-10 080506" src="https://github.com/user-attachments/assets/8d64adbb-d922-4d41-b8f0-cad2aa732fc9" />
<img width="1920" height="1080" alt="Screenshot 2026-11-10 080514" src="https://github.com/user-attachments/assets/992c36ad-ba98-4546-9e99-96baa287c32c" />
<img width="1920" height="1080" alt="Screenshot 2026-11-10 080519" src="https://github.com/user-attachments/assets/4b08c23e-fbce-4f5f-ac08-66fd36ef9cf1" />
<img width="1920" height="1080" alt="Screenshot 2026-11-10 080525" src="https://github.com/user-attachments/assets/7cdd1c61-48ba-4b74-abe3-ec48b4909024" />
<img width="1920" height="1080" alt="Screenshot 2026-11-10 080530" src="https://github.com/user-attachments/assets/e3ee396a-1af7-41e4-a8ef-b1470f79d264" />



## Checklist pengujian

- [ ] Login memvalidasi username dan meneruskannya melalui query parameter.
- [ ] Semua fitur dirender melalui route dan `PageController`.
- [ ] Daftar jemaat, kehadiran, kas, persembahan, dan jadwal berasal dari array controller.
- [ ] Navigasi sidebar aktif dan tetap meneruskan username.
- [ ] Penambahan jemaat, absensi, kas, persembahan, dan jadwal terlihat saat halaman dibuka kembali.
- [ ] Data runtime kembali ke contoh awal setelah server dimulai ulang.
- [ ] Tabel responsif dan layout dapat digunakan pada layar kecil.
- [ ] QRIS ditandai jelas sebagai placeholder; form tidak mengklaim menyimpan data.
- [ ] `php artisan test` lulus.
