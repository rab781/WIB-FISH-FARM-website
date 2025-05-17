<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'alamat_jalan',
        'no_hp',
        'google_id',
        'google_token',
        'google_refresh_token',
        'avatar',
        'foto',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    // Relationships
    /**
     * Get the provinsi associated with the user.
     */
    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }

    /**
     * Get the kabupaten associated with the user.
     */
    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class);
    }

    /**
     * Get the kecamatan associated with the user.
     */
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    /**
     * Get the provinsi object via relationship
     */
    public function getProvinsi()
    {
        return $this->provinsi ?? null;
    }

    /**
     * Get the kabupaten name via relationship
     */
    public function getKabupaten()
    {
        return $this->kabupaten ?? null;
    }

    /**
     * Get the kecamatan name via relationship
     */
    public function getKecamatan()
    {
        return $this->kecamatan ?? null;
    }

    // Relasi ke keranjang
    public function keranjang()
    {
        return $this->hasMany(Keranjang::class, 'user_id', 'id');
    }

    // Relasi ke pesanan
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'user_id', 'id');
    }

    // Relasi ke ulasan
    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'user_id', 'id');
    }

    // Relasi ke notifikasi
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id');
    }

}
