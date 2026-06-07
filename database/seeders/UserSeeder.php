<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'role_id' => Role::where('name', 'Superadmin')->first()->id,
            'name' => 'Marcell Yoel',
            'username' => 'cello',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345'),
        ]);
    }
}
