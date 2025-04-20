<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory, SoftDeletes;

    // Nama tabel yang digunakan
    protected $table = 'produk';

    // Primary key
    protected $primaryKey = 'id_Produk';

    // Atribut yang dapat diisi
    protected $fillable = [
        'nama_ikan',
        'deskripsi',
        'stok',
        'harga',
        'jenis_ikan',
        'gambar',
    ];

    // Relasi ke keranjang
    public function keranjang()
    {
        return $this->hasMany(Keranjang::class, 'id_Produk', 'id_Produk');
    }

    // Relasi ke detail pesanan
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_Produk', 'id_Produk');
    }

    // Relasi ke ulasan
    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_Produk', 'id_Produk');
    }
}
