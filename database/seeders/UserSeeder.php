<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        // 1. Buat akun Ketua RW
        User::create([
            'name' => 'Ketua RW 04',
            'email' => 'rw@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin_rw',
            'rt_number' => null,
        ]);

        // 2. Buat 5 akun Ketua RT (RT 01 sampai RT 05)
        for ($i = 1; $i <= 5; $i++) {
            $rtNumber = str_pad($i, 2, '0', STR_PAD_LEFT);
            
            User::create([
                'name' => "Ketua RT {$rtNumber}",
                'email' => "rt{$rtNumber}@test.com",
                'password' => Hash::make('password'),
                'role' => 'admin_rt',
                'rt_number' => $rtNumber,
            ]);
        }

        $this->command->info('✅ User seeder berhasil! 1 Admin RW + 5 Admin RT telah dibuat.');
    }
}
