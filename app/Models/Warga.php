<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Warga extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'warga';

    /**
     * Atribut yang dapat diisi secara mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kk_id',
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'status_perkawinan',
        'pekerjaan',
        'status_keluarga',
        'status_kependudukan',
        'status_dasar',
    ];

    /**
     * Atribut yang harus di-cast ke tipe native.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Relasi many-to-one dengan model KartuKeluarga.
     * Banyak Warga tergabung dalam satu Kartu Keluarga.
     *
     * @return BelongsTo
     */
    public function kartuKeluarga(): BelongsTo
    {
        return $this->belongsTo(KartuKeluarga::class, 'kk_id');
    }

    /**
     * Accessor untuk mendapatkan umur warga.
     *
     * @return int
     */
    public function getUmurAttribute(): int
    {
        return Carbon::parse($this->tanggal_lahir)->age;
    }

    /**
     * Accessor untuk mendapatkan tempat dan tanggal lahir dalam format lengkap.
     *
     * @return string
     */
    public function getTempatTanggalLahirAttribute(): string
    {
        return "{$this->tempat_lahir}, " . $this->tanggal_lahir->format('d-m-Y');
    }

    /**
     * Scope untuk filter warga yang masih hidup.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHidup($query)
    {
        return $query->where('status_dasar', 'Hidup');
    }

    /**
     * Scope untuk filter warga yang meninggal.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMeninggal($query)
    {
        return $query->where('status_dasar', 'Meninggal');
    }

    /**
     * Scope untuk filter warga yang pindah.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePindah($query)
    {
        return $query->where('status_dasar', 'Pindah');
    }

    /**
     * Scope untuk mendapatkan warga yang berulang tahun hari ini.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUlangTahunHariIni($query)
    {
        $today = Carbon::today();
        return $query->whereMonth('tanggal_lahir', $today->month)
                     ->whereDay('tanggal_lahir', $today->day)
                     ->where('status_dasar', 'Hidup');
    }
}
