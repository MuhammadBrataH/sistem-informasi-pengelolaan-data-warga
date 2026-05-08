<?php

namespace Database\Seeders;

use App\Models\KartuKeluarga;
use App\Models\Warga;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class WargaSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Gunakan locale Indonesia
        
        // Daftar agama di Indonesia
        $agamaList = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
        
        // Daftar status perkawinan
        $statusPerkawinanList = ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'];
        
        // Daftar pekerjaan
        $pekerjaanList = [
            'Wiraswasta', 'PNS', 'Karyawan Swasta', 'Buruh', 'Petani', 
            'Pedagang', 'Guru', 'Dokter', 'Pengusaha', 'Ibu Rumah Tangga',
            'Pelajar/Mahasiswa', 'Belum/Tidak Bekerja'
        ];

        // Data khusus untuk testing fitur ulang tahun
        $today = Carbon::now(); // 2026-01-04
        $tomorrow = Carbon::now()->addDay(); // 2026-01-05

        // Buat 5 Kartu Keluarga
        for ($kkIndex = 1; $kkIndex <= 5; $kkIndex++) {
            $rtNumber = str_pad($kkIndex, 2, '0', STR_PAD_LEFT);
            $namaKepalaKeluarga = $faker->name('male');
            
            // Buat Kartu Keluarga
            $kk = KartuKeluarga::create([
                'nomor_kk' => '3273' . $faker->unique()->numerify('############'),
                'kepala_keluarga' => $namaKepalaKeluarga,
                'alamat' => $faker->streetAddress(),
                'rt' => $rtNumber,
                'rw' => '04',
            ]);

            // Tentukan jumlah anggota keluarga (3-4 orang)
            $jumlahAnggota = rand(3, 4);
            
            // Anggota 1: Kepala Keluarga (Laki-laki, Kawin)
            $tanggalLahirKepala = $faker->dateTimeBetween('-55 years', '-30 years');
            
            // KHUSUS: KK pertama, buat kepala keluarga dengan ulang tahun HARI INI
            if ($kkIndex === 1) {
                $tanggalLahirKepala = Carbon::create(
                    $faker->numberBetween(1970, 1990),
                    $today->month,  // Bulan hari ini (Januari)
                    $today->day,    // Tanggal hari ini (4)
                );
            }

            Warga::create([
                'kk_id' => $kk->id,
                'nik' => '3273' . $faker->unique()->numerify('############'),
                'nama_lengkap' => $namaKepalaKeluarga,
                'tempat_lahir' => $faker->city(),
                'tanggal_lahir' => $tanggalLahirKepala,
                'jenis_kelamin' => 'L',
                'agama' => $faker->randomElement($agamaList),
                'status_perkawinan' => 'Kawin',
                'pekerjaan' => $faker->randomElement(['Wiraswasta', 'PNS', 'Karyawan Swasta', 'Pengusaha']),
                'status_keluarga' => 'Kepala Keluarga',
                'status_kependudukan' => 'Tetap',
                'status_dasar' => 'Hidup',
            ]);

            // Anggota 2: Istri (Perempuan, Kawin)
            $tanggalLahirIstri = $faker->dateTimeBetween('-50 years', '-25 years');
            
            // KHUSUS: KK kedua, buat istri dengan ulang tahun BESOK
            if ($kkIndex === 2) {
                $tanggalLahirIstri = Carbon::create(
                    $faker->numberBetween(1975, 1995),
                    $tomorrow->month,  // Bulan besok (Januari)
                    $tomorrow->day,    // Tanggal besok (5)
                );
            }

            Warga::create([
                'kk_id' => $kk->id,
                'nik' => '3273' . $faker->unique()->numerify('############'),
                'nama_lengkap' => $faker->name('female'),
                'tempat_lahir' => $faker->city(),
                'tanggal_lahir' => $tanggalLahirIstri,
                'jenis_kelamin' => 'P',
                'agama' => $faker->randomElement($agamaList),
                'status_perkawinan' => 'Kawin',
                'pekerjaan' => $faker->randomElement(['Ibu Rumah Tangga', 'Guru', 'Karyawan Swasta', 'Wiraswasta']),
                'status_keluarga' => 'Istri',
                'status_kependudukan' => 'Tetap',
                'status_dasar' => 'Hidup',
            ]);

            // Anggota 3 dan seterusnya: Anak/Famili Lain
            for ($i = 3; $i <= $jumlahAnggota; $i++) {
                $jenisKelamin = $faker->randomElement(['L', 'P']);
                $statusKeluarga = ($i <= 3) ? 'Anak' : 'Famili Lain';
                $umur = ($statusKeluarga === 'Anak') ? rand(1, 25) : rand(20, 60);
                
                $tanggalLahirAnak = $faker->dateTimeBetween("-{$umur} years", "-" . ($umur - 5) . " years");
                
                // KHUSUS: KK ketiga, anak pertama ulang tahun HARI INI
                if ($kkIndex === 3 && $i === 3) {
                    $tanggalLahirAnak = Carbon::create(
                        $faker->numberBetween(2000, 2015),
                        $today->month,
                        $today->day,
                    );
                }

                // KHUSUS: KK keempat, anak pertama ulang tahun BESOK
                if ($kkIndex === 4 && $i === 3) {
                    $tanggalLahirAnak = Carbon::create(
                        $faker->numberBetween(2005, 2018),
                        $tomorrow->month,
                        $tomorrow->day,
                    );
                }

                // Tentukan pekerjaan sesuai umur
                $pekerjaan = $umur < 18 
                    ? 'Pelajar/Mahasiswa' 
                    : $faker->randomElement($pekerjaanList);

                $statusPerkawinan = $umur < 18 
                    ? 'Belum Kawin' 
                    : $faker->randomElement(['Belum Kawin', 'Kawin']);

                Warga::create([
                    'kk_id' => $kk->id,
                    'nik' => '3273' . $faker->unique()->numerify('############'),
                    'nama_lengkap' => $faker->name($jenisKelamin === 'L' ? 'male' : 'female'),
                    'tempat_lahir' => $faker->city(),
                    'tanggal_lahir' => $tanggalLahirAnak,
                    'jenis_kelamin' => $jenisKelamin,
                    'agama' => $faker->randomElement($agamaList),
                    'status_perkawinan' => $statusPerkawinan,
                    'pekerjaan' => $pekerjaan,
                    'status_keluarga' => $statusKeluarga,
                    'status_kependudukan' => 'Tetap',
                    'status_dasar' => 'Hidup',
                ]);
            }

            // KHUSUS: Untuk KK kelima, tambahkan 1 warga yang meninggal dan 1 yang pindah
            if ($kkIndex === 5) {
                // Warga yang meninggal
                Warga::create([
                    'kk_id' => $kk->id,
                    'nik' => '3273' . $faker->unique()->numerify('############'),
                    'nama_lengkap' => $faker->name('male'),
                    'tempat_lahir' => $faker->city(),
                    'tanggal_lahir' => $faker->dateTimeBetween('-70 years', '-60 years'),
                    'jenis_kelamin' => 'L',
                    'agama' => $faker->randomElement($agamaList),
                    'status_perkawinan' => 'Kawin',
                    'pekerjaan' => 'Pensiunan',
                    'status_keluarga' => 'Famili Lain',
                    'status_kependudukan' => 'Tetap',
                    'status_dasar' => 'Meninggal', // Status mutasi: Meninggal
                ]);

                // Warga yang pindah
                Warga::create([
                    'kk_id' => $kk->id,
                    'nik' => '3273' . $faker->unique()->numerify('############'),
                    'nama_lengkap' => $faker->name('female'),
                    'tempat_lahir' => $faker->city(),
                    'tanggal_lahir' => $faker->dateTimeBetween('-30 years', '-20 years'),
                    'jenis_kelamin' => 'P',
                    'agama' => $faker->randomElement($agamaList),
                    'status_perkawinan' => 'Kawin',
                    'pekerjaan' => 'Ibu Rumah Tangga',
                    'status_keluarga' => 'Anak',
                    'status_kependudukan' => 'Tetap',
                    'status_dasar' => 'Pindah', // Status mutasi: Pindah
                ]);
            }

            $this->command->info("✅ Kartu Keluarga {$kkIndex} ({$kk->nomor_kk}) - RT {$rtNumber} berhasil dibuat dengan {$jumlahAnggota} anggota.");
        }

        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info('   - Total KK: ' . KartuKeluarga::count());
        $this->command->info('   - Total Warga: ' . Warga::count());
        $this->command->info('   - Warga Hidup: ' . Warga::hidup()->count());
        $this->command->info('   - Warga Meninggal: ' . Warga::meninggal()->count());
        $this->command->info('   - Warga Pindah: ' . Warga::pindah()->count());
        $this->command->info('   - Warga Ulang Tahun Hari Ini (4 Januari): ' . Warga::ulangTahunHariIni()->count());
        $this->command->info('');
        $this->command->info('✨ Warga seeder berhasil!');
    }
}
