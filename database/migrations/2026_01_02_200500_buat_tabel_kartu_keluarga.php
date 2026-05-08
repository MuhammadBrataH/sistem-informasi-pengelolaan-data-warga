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
        Schema::create('kartu_keluarga', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kk', 16)->unique()->comment('Nomor Kartu Keluarga (16 digit)');
            $table->string('kepala_keluarga')->comment('Nama Kepala Keluarga');
            $table->text('alamat')->comment('Alamat lengkap keluarga');
            $table->string('rt')->comment('Nomor RT');
            $table->string('rw')->default('04')->comment('Nomor RW');
            $table->timestamps();
        });
    }

    /**
     * Balikkan migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartu_keluarga');
    }
};
