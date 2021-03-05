<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'phone' => '01023779579',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'role_id'  => 1,
        ]);
    }
}
