<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan
    protected $table = 'ulasan';

    // Primary key
    protected $primaryKey = 'id_ulasan';

    // Atribut yang dapat diisi
    protected $fillable = [
        'user_id',
        'id_Produk',
        'rating',
        'komentar',
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
}
