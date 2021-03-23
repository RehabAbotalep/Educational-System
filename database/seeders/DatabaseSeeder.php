<?php

namespace Database\Seeders;

use App\Models\ExamType;
use App\Models\Role;
use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['Admin', 'Teacher', 'Student', 'Secretary', 'Manager'];

        foreach ($roles as $role){
            Role::create([
                'name' => $role,
            ]);
        }

        $examTypes = ['True&false', 'Choice', 'essays'];

        foreach ($examTypes as $type){
            ExamType::create([
                'name' => $type,
            ]);
        }

        User::create([
            'name' => 'Admin',
            'phone' => '01023779579',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role_id'  => 1,
        ]);
    }
}
