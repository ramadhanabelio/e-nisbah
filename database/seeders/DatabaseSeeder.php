<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'role' => 'cs',
                'name' => 'Nama CS',
            ],
            [
                'role' => 'pinsi_pelnas',
                'name' => 'Nama PINSI PELNAS',
            ],
            [
                'role' => 'pinbag_operasional',
                'name' => 'Nama PINBAG Operasional',
            ],
            [
                'role' => 'pincab',
                'name' => 'Nama PINCAB',
            ],
            [
                'role' => 'admin_pusat',
                'name' => 'Nama Admin Kantor Pusat',
            ],
            [
                'role' => 'pinbag',
                'name' => 'Nama PINBAG',
            ],
            [
                'role' => 'pinidiv',
                'name' => 'Nama PINIDIV',
            ],
            [
                'role' => 'direksi',
                'name' => 'Nama Direksi',
            ],
            [
                'role' => 'dirut',
                'name' => 'Nama DIRUT',
            ],
        ];

        foreach ($users as $index => $user) {
            $number = $index + 1;
            $nik = str_repeat((string) $number, 20);

            User::create([
                'nik' => $nik,
                'name' => $user['name'],
                'email' => $user['role'] . '@brksyariah.co.id',
                'password' => Hash::make($nik),
                'role' => $user['role'],
                'is_active' => true,
            ]);
        }
    }
}
