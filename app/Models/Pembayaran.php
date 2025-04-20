<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan
    protected $table = 'pembayaran';

    // Primary key
    protected $primaryKey = 'id_pembayaran';

    // Atribut yang dapat diisi
    protected $fillable = [
        'id_pesanan',
        'status_pembayaran',
        'nomor_rekening',
        'nama_bank',
    ];

    // Cast atribut
    protected $casts = [
        'status_pembayaran' => 'boolean',
    ];

    // Relasi ke pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}
