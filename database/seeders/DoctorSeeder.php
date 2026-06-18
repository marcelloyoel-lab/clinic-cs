<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'role_id' => 4,
                'username' => 'drjohndoe',
                'name' => 'Dr. John Doe',
                'email' => 'john.doe@clinic.test',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 4,
                'username' => 'drjanesmith',
                'name' => 'Dr. Jane Smith',
                'email' => 'jane.smith@clinic.test',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 4,
                'username' => 'drmichaellee',
                'name' => 'Dr. Michael Lee',
                'email' => 'michael.lee@clinic.test',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
