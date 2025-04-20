<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan
    protected $table = 'detail_pesanan';

    // Primary key komposit, tidak menggunakan auto-increment
    public $incrementing = false;
    protected $primaryKey = ['id_pesanan', 'id_Produk'];

    // Atribut yang dapat diisi
    protected $fillable = [
        'id_pesanan',
        'id_Produk',
        'jumlah',
        'total_harga',
    ];

    // Relasi ke pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    // Relasi ke produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_Produk', 'id_Produk');
    }
}
