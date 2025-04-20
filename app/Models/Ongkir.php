<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ongkir extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan
    protected $table = 'ongkir';

    // Primary key
    protected $primaryKey = 'id_ongkir';

    // Atribut yang dapat diisi
    protected $fillable = [
        'nama_kota',
        'berat',
        'harga',
    ];

    // Relasi ke pesanan
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_ongkir', 'id_ongkir');
    }
}
