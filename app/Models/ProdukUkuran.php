<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdukUkuran extends Model
{
    protected $table = 'produk_ukuran';

    protected $fillable = [
        'id_produk',
        'ukuran',
        'stok',
        'harga',
        'is_active'
    ];

    /**
     * Relasi ke produk
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_Produk');
    }

    /**
     * Mendapatkan harga ukuran, jika tidak ada gunakan harga default produk
     */
    public function getHargaAttribute($value)
    {
        return $value ?: $this->produk->harga;
    }

    /**
     * Scope untuk memfilter ukuran yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk memfilter ukuran yang tersedia (stok > 0)
     */
    public function scopeInStock($query)
    {
        return $query->where('stok', '>', 0);
    }
}
