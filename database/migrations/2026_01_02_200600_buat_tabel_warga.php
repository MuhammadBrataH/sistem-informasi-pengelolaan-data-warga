<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrations.
     */
    public function up(): void
    {
        Schema::create('warga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kk_id')
                ->constrained('kartu_keluarga')
                ->onDelete('cascade')
                ->comment('Relasi ke tabel kartu_keluarga');
            
            $table->string('nik', 16)->unique()->comment('Nomor Induk Kependudukan (16 digit)');
            $table->string('nama_lengkap')->comment('Nama lengkap warga');
            $table->string('tempat_lahir')->comment('Tempat lahir warga');
            $table->date('tanggal_lahir')->comment('Tanggal lahir warga');
            $table->enum('jenis_kelamin', ['L', 'P'])->comment('Jenis kelamin: L=Laki-laki, P=Perempuan');
            $table->string('agama')->comment('Agama warga');
            $table->string('status_perkawinan')->comment('Status perkawinan warga');
            $table->string('pekerjaan')->comment('Pekerjaan warga');
            $table->enum('status_keluarga', ['Kepala Keluarga', 'Istri', 'Anak', 'Famili Lain'])
                ->comment('Status dalam keluarga');
            $table->enum('status_kependudukan', ['Tetap', 'Kontrak'])
                ->default('Tetap')
                ->comment('Status kependudukan di wilayah');
            $table->enum('status_dasar', ['Hidup', 'Meninggal', 'Pindah'])
                ->default('Hidup')
                ->comment('Status dasar warga untuk penanganan mutasi');
            
            $table->timestamps();
        });
    }

    /**
     * Balikkan migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warga');
    }
};
