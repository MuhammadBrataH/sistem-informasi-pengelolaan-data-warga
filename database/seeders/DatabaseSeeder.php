<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        // Panggil seeder untuk User (Admin RW & RT)
        $this->call([
            UserSeeder::class,
            WargaSeeder::class,
        ]);
    }
}
