<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KartuKeluarga extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'kartu_keluarga';

    /**
     * Atribut yang dapat diisi secara mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nomor_kk',
        'kepala_keluarga',
        'alamat',
        'rt',
        'rw',
    ];

    /**
     * Relasi one-to-many dengan model Warga.
     * Satu Kartu Keluarga memiliki banyak Warga.
     *
     * @return HasMany
     */
    public function warga(): HasMany
    {
        return $this->hasMany(Warga::class, 'kk_id');
    }

    /**
     * Relasi untuk mendapatkan warga yang masih hidup saja.
     *
     * @return HasMany
     */
    public function wargaHidup(): HasMany
    {
        return $this->hasMany(Warga::class, 'kk_id')->where('status_dasar', 'Hidup');
    }

    /**
     * Accessor untuk mendapatkan alamat lengkap dengan RT/RW.
     *
     * @return string
     */
    public function getAlamatLengkapAttribute(): string
    {
        return "{$this->alamat}, RT {$this->rt}/RW {$this->rw}";
    }
}
