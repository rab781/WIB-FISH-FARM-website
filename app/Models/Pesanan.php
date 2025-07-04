<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    // Atribut dates untuk konversi otomatis ke Carbon
    protected $dates = [
        'created_at',
        'updated_at',
        'batas_waktu',
        'tanggal_pengiriman',
        'tanggal_diterima'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'batas_waktu' => 'datetime',
        'tanggal_pengiriman' => 'datetime',
        'tanggal_diterima' => 'datetime',
        'tracking_history' => 'array',
        'total_harga' => 'decimal:2',
        'ongkir_biaya' => 'decimal:2',
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
        // Tracking fields
        'no_resi',
        'tanggal_pengiriman',
        'tanggal_diterima',
        'tracking_history',
        'kondisi_diterima',
        'catatan_penerimaan',
        'alasan_pembatalan',
        'bukti_pembayaran',
        'tanggal_pembayaran',
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

    // Enhanced relationships for new features
    // Use pengembalian for all refund/return functionality

    public function pengembalian()
    {
        return $this->hasMany(Pengembalian::class, 'id_pesanan', 'id_pesanan');
    }

    // Relasi ke ulasan/reviews
    public function ulasanPesanan()
    {
        return $this->hasMany(Ulasan::class, 'id_pesanan', 'id_pesanan');
    }

    public function timeline()
    {
        return $this->hasMany(OrderTimeline::class, 'id_pesanan', 'id_pesanan')
                   ->orderBy('created_at');
    }

    // Status management methods
    public function getStatusColorAttribute(): string
    {
        return match($this->status_pesanan) {
            'Menunggu Pembayaran' => 'warning',
            'Pembayaran Dikonfirmasi' => 'info',
            'Diproses' => 'orange',
            'Pengembalian' => 'orange',
            'Dikirim' => 'primary',
            'Selesai' => 'success',
            'Dibatalkan' => 'danger',
            default => 'dark'
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        $color = $this->status_color;
        return "<span class='badge bg-{$color}'>{$this->status_pesanan}</span>";
    }

    // Tracking and shipping methods
    public function updateTracking(array $trackingData): void
    {
        $currentHistory = $this->tracking_history ?? [];
        $currentHistory[] = array_merge($trackingData, [
            'timestamp' => now()->toISOString()
        ]);

        $this->update(['tracking_history' => $currentHistory]);
    }

    public function ship(string $noResi): void
    {
        $this->update([
            'status_pesanan' => 'Dikirim',
            'no_resi' => $noResi,
            'tanggal_pengiriman' => now()
        ]);

        $this->addTimelineEntry('Dikirim', 'Pesanan Dikirim',
            "Pesanan telah dikirim dengan nomor resi: {$noResi}");
    }

    public function markAsDelivered(string $kondisi = 'baik', ?string $catatan = null): void
    {
        $this->update([
            'status_pesanan' => 'Selesai',
            'tanggal_diterima' => now(),
            'kondisi_diterima' => $kondisi,
            'catatan_penerimaan' => $catatan,
            'is_reviewable' => true
        ]);

        $this->addTimelineEntry('Selesai', 'Pesanan Diterima',
            "Pesanan telah diterima dengan kondisi: {$kondisi}");
    }

    // Helper methods
    public function getTotalItemsAttribute(): int
    {
        return $this->detailPesanan->sum('kuantitas');
    }

    // Accessor for backward compatibility with views that use nomor_resi
    public function getNomorResiAttribute()
    {
        return $this->no_resi;
    }

    // Accessor for nomor_pesanan to provide consistent order number format
    public function getNomorPesananAttribute()
    {
        return 'ORD' . str_pad($this->id_pesanan, 6, '0', STR_PAD_LEFT);
    }

    public function getIsTrackableAttribute(): bool
    {
        return !empty($this->no_resi) && in_array($this->status_pesanan, ['Dikirim']);
    }

    public function getFormattedTotalHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getIsEligibleForReturnAttribute(): bool
    {
        // Pesanan dapat diajukan pengembalian jika:
        // 1. Status pesanan selesai
        // 2. Belum ada pengajuan pengembalian sebelumnya
        // 3. Masih dalam waktu yang ditentukan (24 jam setelah diterima)
        return $this->status_pesanan === 'Selesai' &&
               !$this->pengembalian()->exists() &&
               $this->tanggal_diterima &&
               $this->tanggal_diterima->copy()->addHours(24)->isFuture();
    }

    public function getUlasanAttribute()
    {
        // Get reviews specifically for this order
        return \App\Models\Ulasan::where('id_pesanan', $this->id_pesanan)
            ->where('user_id', $this->user_id)
            ->with(['produk', 'user'])
            ->get();
    }

    public function getOrderReviews()
    {
        // Alias method for getUlasanAttribute() for consistency with view expectations
        return $this->ulasan;
    }

    public function getReviewableProductsAttribute()
    {
        // Get all product IDs from this order that haven't been reviewed yet for THIS specific order
        $productIds = $this->detailPesanan->pluck('id_Produk')->toArray();

        // Get already reviewed product IDs for THIS specific order
        $reviewedProductIds = \App\Models\Ulasan::where('user_id', $this->user_id)
            ->where('id_pesanan', $this->id_pesanan) // Only check reviews for this specific order
            ->whereIn('id_Produk', $productIds)
            ->pluck('id_Produk')
            ->toArray();

        // Calculate products that can be reviewed
        $reviewableProductIds = array_diff($productIds, $reviewedProductIds);

        // Return corresponding detail pesanan items for these products
        return $this->detailPesanan->filter(function($detail) use ($reviewableProductIds) {
            return in_array($detail->id_Produk, $reviewableProductIds);
        });
    }

    public function getIsReviewableAttribute(): bool
    {
        // Pesanan dapat direview jika:
        // 1. Status pesanan selesai
        // 2. Masih ada produk yang belum direview
        // Note: Removed tanggal_diterima requirement - status "Selesai" is sufficient indicator
        return $this->status_pesanan === 'Selesai' &&
               $this->reviewable_products->isNotEmpty();
    }
}
