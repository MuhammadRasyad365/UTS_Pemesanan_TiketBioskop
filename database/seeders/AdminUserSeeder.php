<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@elcinema.test',
            ],
            [
                'name' => 'Administrator ELCinema',
                'password' => Hash::make('ELCinemaAdmin#2026'),
                'role' => 'admin',
            ]
        );
    }
}
