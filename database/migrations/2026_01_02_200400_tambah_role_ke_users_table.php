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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin_rw', 'admin_rt'])->default('admin_rt')->after('email');
            $table->string('rt_number')->nullable()->after('role')->comment('Nomor RT, hanya diisi jika role admin_rt');
        });
    }

    /**
     * Balikkan migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'rt_number']);
        });
    }
};
