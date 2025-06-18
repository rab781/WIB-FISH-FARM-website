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
        'kuantitas',
        'harga',
        'subtotal',
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

    // Relasi ke ulasan untuk detail pesanan ini
    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_Produk', 'id_Produk')
                    ->where('is_verified_purchase', true);
    }

    // Method untuk mendapatkan ulasan dari user yang sama dengan pesanan
    public function userUlasan()
    {
        return $this->hasMany(Ulasan::class, 'id_Produk', 'id_Produk')
                    ->where('id_pesanan', $this->id_pesanan) // Filter by specific order
                    ->whereColumn('user_id', function($query) {
                        $query->select('user_id')
                              ->from('pesanan')
                              ->whereColumn('id_pesanan', 'detail_pesanan.id_pesanan')
                              ->limit(1);
                    })
                    ->where('is_verified_purchase', true);
    }

    // Method untuk mendapatkan ulasan terkait dengan detail pesanan ini
    public function getReviewsAttribute()
    {
        // Pastikan pesanan sudah ter-load
        if (!$this->relationLoaded('pesanan')) {
            $this->load('pesanan');
        }

        // Mendapatkan user_id dari pesanan
        $userId = $this->pesanan->user_id ?? null;

        if (!$userId) {
            return collect();
        }

        // Mencari ulasan berdasarkan user_id, id_Produk dan id_pesanan spesifik
        return Ulasan::where('user_id', $userId)
            ->where('id_Produk', $this->id_Produk)
            ->where('id_pesanan', $this->id_pesanan) // Filter by specific order
            ->where('is_verified_purchase', true)
            ->get();
    }

    // Method untuk mengecek apakah ada ulasan untuk detail pesanan ini
    public function hasReviews()
    {
        return $this->reviews->isNotEmpty();
    }
}
