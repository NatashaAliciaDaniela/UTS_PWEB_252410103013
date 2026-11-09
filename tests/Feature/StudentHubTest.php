<?php

namespace Tests\Feature;

use Tests\TestCase;

class StudentHubTest extends TestCase
{
    public function test_login_page_is_available(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Welcome Back')
            ->assertSee('images/kampus-universitas-jember.png')
            ->assertSee('name="username"', false);
    }

    public function test_login_requires_a_non_empty_username(): void
    {
        $this->post('/login', ['username' => '   '])
            ->assertOk()
            ->assertSee('The username field is required.');
    }

    public function test_login_redirects_with_username_in_query_string(): void
    {
        $this->post('/login', ['username' => '  Rio  '])
            ->assertRedirect('/dashboard?username=Rio');
    }

    public function test_dashboard_and_profile_receive_the_username(): void
    {
        $this->get('/dashboard?username=Rio')
            ->assertOk()
            ->assertSee('Selamat datang, Rio')
            ->assertSee('images/kampus-universitas-jember.png')
            ->assertSee('Total Mahasiswa');

        $this->get('/profile?username=Rio')
            ->assertOk()
            ->assertSee('Rio')
            ->assertSee('Profil Pengguna');
    }

    public function test_student_management_lists_all_eight_students(): void
    {
        $this->get('/pengelolaan?username=Rio')
            ->assertOk()
            ->assertSee('Pengelolaan Mahasiswa')
            ->assertSee('Andi Pratama')
            ->assertSee('Alya Safitri')
            ->assertSee('Menampilkan 8 dari 8 mahasiswa');
    }

    public function test_pages_without_username_return_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/');
        $this->get('/profile')->assertRedirect('/');
        $this->get('/pengelolaan')->assertRedirect('/');
    }
}
