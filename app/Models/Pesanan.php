<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    // Atribut dates untuk konversi otomatis ke Carbon
    protected $dates = ['created_at', 'updated_at', 'batas_waktu'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'batas_waktu' => 'datetime',
    ];

    // Nama tabel yang digunakan
    protected $table = 'pesanan';

    // Primary key
    protected $primaryKey = 'id_pesanan';

    // Atribut yang dapat diisi
    protected $fillable = [
        'user_id',
        'id_ongkir',
        'total_harga',
        'status_pesanan',
        'alamat_pengiriman',
        'metode_pembayaran',
        'batas_waktu',
        'alamat_id',
        'kurir',
        'kurir_service',
        'ongkir_biaya',
        'berat_total',
        'jumlah_box',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi ke ongkir
    public function ongkir()
    {
        return $this->belongsTo(Ongkir::class, 'id_ongkir', 'id_ongkir');
    }

    // Relasi ke alamat
    public function alamat()
    {
        return $this->belongsTo(Alamat::class, 'alamat_id', 'id');
    }

    // Relasi ke detail pesanan
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }

    // Relasi ke pembayaran
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pesanan', 'id_pesanan');
    }
}
