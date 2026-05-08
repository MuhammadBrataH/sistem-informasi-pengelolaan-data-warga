<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi secara mass assignment.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'rt_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Cek apakah user adalah Admin RW.
     *
     * @return bool
     */
    public function isAdminRW(): bool
    {
        return $this->role === 'admin_rw';
    }

    /**
     * Cek apakah user adalah Admin RT.
     *
     * @return bool
     */
    public function isAdminRT(): bool
    {
        return $this->role === 'admin_rt';
    }

    /**
     * Scope untuk filter user berdasarkan role Admin RW.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdminRW($query)
    {
        return $query->where('role', 'admin_rw');
    }

    /**
     * Scope untuk filter user berdasarkan role Admin RT.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdminRT($query)
    {
        return $query->where('role', 'admin_rt');
    }
}
