<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan
    protected $table = 'keranjang';

    // Primary key
    protected $primaryKey = 'id_keranjang';

    // Atribut yang dapat diisi
    protected $fillable = [
        'user_id',
        'id_Produk',
        'ukuran_id',
        'jumlah',
        'total_harga',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi ke produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_Produk', 'id_Produk');
    }

    // Relasi ke ukuran produk
    public function ukuran()
    {
        return $this->belongsTo(ProdukUkuran::class, 'ukuran_id', 'id');
    }
}
