<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StaffTest extends TestCase
{
    private $token;

    public function getToken()
    {
        $data = [
            'email' => 'admin@admin.com',
            'password' => '12345678'
        ];
        $response = $this->json('post', '/api/auth/login', $data);

        $response->assertJson(['data' => ['role_name' => 'admin']]);

        $this->token = $response['data']['token'];
        return $this->token;

    }


    public function testGetAllStaff()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getToken(),
        ])->json('GET', 'api/admin/staff/all');

        $response->assertStatus(200);
        dd($response);


    }
}
