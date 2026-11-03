<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;

class User extends Authenticatable implements LdapAuthenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, AuthenticatesWithLdap;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'nidn',
        'fakultas',
        'prodi',
        'jabatan_fungsional',
        'role',
        'tanda_tangan', // <--- TAMBAHKAN INI
        'guid',
        'domain'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Atribut yang dikonversi ke tipe data tertentu.
     */
    protected static function booted()
    {
        static::saving(function ($user) {
            // Jika ini user LDAP (ada GUID), paksa password tetap NULL
            if ($user->guid) {
                $user->password = null;
            }
        });
    }

    /**
     * Mutator: Hanya hash jika ada input (untuk Admin/Lokal)
     */
    public function setPasswordAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['password'] = null;
        } else {
            // Cek jika password sudah di-hash (dimulai dengan $2y$) jangan di-hash lagi
            $this->attributes['password'] = \Illuminate\Support\Facades\Hash::needsRehash($value)
                ? bcrypt($value)
                : $value;
        }
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed', // AKTIFKAN KEMBALI agar login lokal bisa validasi
        ];
    }

    /**
     * MENIADAKAN FITUR REMEMBER TOKEN
     * Menimpa fungsi bawaan Laravel agar tidak mencari kolom remember_token di DB
     */
    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        // Kosongkan agar tidak mencoba menyimpan ke database
    }

    public function getRememberTokenName()
    {
        return null;
    }
}