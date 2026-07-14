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
                'name' => 'Sri Mulyani Adha',
            ],
            [
                'role' => 'pinsi_pelnas',
                'name' => 'Herda Alhusna',
            ],
            [
                'role' => 'pinbag_operasional',
                'name' => 'Rahma Yeni',
            ],
            [
                'role' => 'pincab',
                'name' => 'Hadi Kesuma',
            ],
            [
                'role' => 'admin_pusat',
                'name' => 'Nurhidayah',
            ],
            [
                'role' => 'pinbag',
                'name' => 'Melani Usman',
            ],
            [
                'role' => 'pinidiv',
                'name' => 'Famela',
            ],
            [
                'role' => 'direksi',
                'name' => 'Okta',
            ],
            [
                'role' => 'dirut',
                'name' => 'Vianda',
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
