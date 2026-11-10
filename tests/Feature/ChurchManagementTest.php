<?php

namespace Tests\Feature;

use App\Support\ChurchRuntimeStore;
use Tests\TestCase;

class ChurchManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        ChurchRuntimeStore::reset();
    }

    public function test_login_validates_username_and_redirects_using_query_parameter(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('GPdI Dadapan')
            ->assertDontSee('app-sidebar')
            ->assertSee('name="username"', false);

        $this->post('/login', ['username' => '   '])
            ->assertOk()
            ->assertDontSee('app-sidebar')
            ->assertSee('The username field is required.');

        $this->post('/login', ['username' => ' Rio '])
            ->assertRedirect('/dashboard?username=Rio');
    }

    public function test_dashboard_displays_church_overview_and_navigation(): void
    {
        $this->get('/dashboard?username=Rio')
            ->assertOk()
            ->assertSee('Selamat datang, Rio')
            ->assertSee('Total Jemaat')
            ->assertSee('Ibadah Minggu')
            ->assertSee('Data Jemaat')
            ->assertSee('Kas Gereja')
            ->assertSee('poster-ibadah.svg');
    }

    public function test_all_church_pages_render_for_a_valid_username(): void
    {
        foreach ([
            '/jemaat' => 'Data Jemaat',
            '/kehadiran' => 'Kehadiran Ibadah Minggu',
            '/remaja' => 'Kelompok Remaja',
            '/pemuda' => 'Kelompok Pemuda',
            '/kas' => 'Kas Gereja',
            '/persembahan' => 'Persembahan',
            '/ibadah' => 'Informasi Ibadah',
            '/profile' => 'Profil Pengurus',
        ] as $path => $heading) {
            $this->get($path.'?username=Rio')
                ->assertOk()
                ->assertSee($heading);
        }
    }

    public function test_worship_information_page_displays_the_service_poster_and_details(): void
    {
        $this->get('/ibadah?username=Rio')
            ->assertOk()
            ->assertSee('banner-ibadah-november-2026.jpeg')
            ->assertSee('Sunday Service')
            ->assertSee('01 November 2026')
            ->assertSee('07.00 WIB')
            ->assertSee('Sebuah Tanggung Jawab')
            ->assertSee('Pdm. Yohanes Setiawan')
            ->assertSee('Elita Wisma')
            ->assertSee('GPdI “Yesus Segalanya”')
            ->assertSee('@gpdidadapan');
    }

    public function test_member_and_attendance_lists_are_rendered_from_controller_data(): void
    {
        $this->get('/jemaat?username=Rio')
            ->assertOk()
            ->assertSee('Andreas Pratama')
            ->assertSee('Kezia Aprilia')
            ->assertSee('0812-3456-7801');

        $this->get('/kehadiran?username=Rio')
            ->assertOk()
            ->assertSee('15 November 2026')
            ->assertSee('Hadir')
            ->assertSee('Tidak Hadir');
    }

    public function test_group_pages_have_separate_simulated_cash_and_safe_qris_placeholder(): void
    {
        $this->get('/remaja?username=Rio')
            ->assertOk()
            ->assertSee('Iuran persekutuan')
            ->assertSee('qris-placeholder.svg')
            ->assertSee('QRIS belum tersedia');

        $this->get('/pemuda?username=Rio')
            ->assertOk()
            ->assertSee('Iuran pemuda')
            ->assertSee('Perlengkapan ibadah');
    }

    public function test_added_members_are_available_on_other_pages_during_the_same_run(): void
    {
        $this->post('/jemaat?username=Rio', [
            'nama' => 'Nadia Simulasi',
            'gender' => 'Perempuan',
            'kelompok' => 'Remaja',
            'telepon' => '081234567890',
        ])->assertRedirect('/jemaat?username=Rio&saved=1');

        $this->get('/jemaat?username=Rio')->assertSee('Nadia Simulasi');
        $this->get('/remaja?username=Rio')->assertSee('Nadia Simulasi');
    }

    public function test_temporary_transactions_attendance_offerings_and_services_are_added(): void
    {
        $this->post('/remaja/transaksi?username=Rio', [
            'tanggal' => '2026-11-20',
            'keterangan' => 'Transaksi baru remaja',
            'jenis' => 'Pemasukan',
            'nominal' => 75000,
        ])->assertRedirect('/remaja?username=Rio&saved=1');
        $this->get('/remaja?username=Rio')->assertSee('Transaksi baru remaja');
        $this->get('/pemuda?username=Rio')->assertDontSee('Transaksi baru remaja');
        $this->get('/kas?username=Rio')->assertSee('Transaksi baru remaja');

        $this->post('/kehadiran?username=Rio', [
            'kehadiran' => ['Andreas Pratama' => '0'],
        ])->assertRedirect('/kehadiran?username=Rio&saved=1');
        $this->get('/kehadiran?username=Rio')
            ->assertSee('name="kehadiran[Andreas Pratama]"', false)
            ->assertSee('value="0" selected', false);

        $this->post('/persembahan?username=Rio', [
            'tanggal' => '2026-11-22',
            'ibadah' => 'Ibadah Minggu',
            'jenis' => 'Persembahan umum',
            'metode' => 'Tunai',
            'keterangan' => 'Catatan syukur baru',
            'nominal' => 125000,
        ])->assertRedirect('/persembahan?username=Rio&saved=1');
        $this->get('/persembahan?username=Rio')->assertSee('Catatan syukur baru');

        $this->post('/ibadah?username=Rio', [
            'nama' => 'Ibadah Simulasi Baru',
            'tanggal' => '2026-12-01',
            'waktu' => '10:00',
            'tema' => 'Tema baru',
            'pembicara' => 'Pdt. Contoh',
            'lokasi' => 'GPdI Dadapan',
            'deskripsi' => 'Jadwal sementara.',
        ])->assertRedirect('/ibadah?username=Rio&saved=1');
        $this->assertCount(1, ChurchRuntimeStore::get('services'));
        $this->get('/ibadah?username=Rio')->assertSee('Ibadah Simulasi Baru');
    }

    public function test_routes_without_username_return_to_login(): void
    {
        foreach (['/dashboard', '/jemaat', '/kehadiran', '/remaja', '/pemuda', '/kas', '/persembahan', '/ibadah', '/profile'] as $path) {
            $this->get($path)->assertRedirect('/');
        }
    }
}
