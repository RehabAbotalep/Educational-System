<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public $email;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAdminLoginWithCorrectData()
    {
        $data = [
            'email' => 'admin@admin.com',
            'password' => '12345678'
        ];
        $response = $this->json('post', '/api/auth/login', $data);

        $response->assertStatus(200)->assertJson(['data' => ['role_name' => 'admin']]);

        $this->email = $response['data']['email'];

    }

    public function testAdminLoginWithWrongData()
    {
        $data = [
            'email' => 'test@gmail.com',
            'password' => '123456789'
        ];
        $response = $this->json('post', '/api/auth/login', $data);
        $response->assertStatus(200)->assertJson(['status' => 401]);
    }
}
