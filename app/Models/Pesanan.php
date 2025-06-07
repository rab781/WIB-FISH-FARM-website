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
        'karantina_mulai',
        'karantina_selesai',
        'tanggal_refund_request',
        'tanggal_refund_processed',
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
        'karantina_mulai' => 'datetime',
        'karantina_selesai' => 'datetime',
        'tanggal_refund_request' => 'datetime',
        'tanggal_refund_processed' => 'datetime',
        'tanggal_pengiriman' => 'datetime',
        'tanggal_diterima' => 'datetime',
        'tracking_history' => 'array',
        'is_karantina_active' => 'boolean',
        'total_harga' => 'decimal:2',
        'ongkir_biaya' => 'decimal:2',
        'jumlah_refund' => 'decimal:2',
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
        // Karantina fields
        'karantina_mulai',
        'karantina_selesai',
        'is_karantina_active',
        // Refund fields
        'status_refund',
        'alasan_refund',
        'bukti_kerusakan',
        'catatan_admin_refund',
        'tanggal_refund_request',
        'tanggal_refund_processed',
        'jumlah_refund',
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
    public function refundRequests()
    {
        return $this->hasMany(Pengembalian::class, 'id_pesanan', 'id_pesanan');
    }

    public function pengembalian()
    {
        return $this->hasMany(Pengembalian::class, 'id_pesanan', 'id_pesanan');
    }

    public function timeline()
    {
        return $this->hasMany(OrderTimeline::class, 'id_pesanan', 'id_pesanan')
                   ->orderBy('created_at');
    }

    public function quarantineLog()
    {
        return $this->hasOne(QuarantineLog::class, 'id_pesanan', 'id_pesanan');
    }

    public function activeQuarantineLog()
    {
        return $this->hasOne(QuarantineLog::class, 'id_pesanan', 'id_pesanan')
                   ->where('status', 'active');
    }

    // Status management methods
    public function getStatusColorAttribute(): string
    {
        return match($this->status_pesanan) {
            'Menunggu Pembayaran' => 'warning',
            'Pembayaran Dikonfirmasi' => 'info',
            'Diproses' => 'orange',
            'Karantina' => 'purple',
            'Pengembalian' => 'orange',
            'Dikirim' => 'primary',
            'Selesai' => 'success',
            'Dibatalkan' => 'danger',
            'Refund Requested', 'Refund Approved', 'Refund Rejected', 'Refund Processed' => 'secondary',
            default => 'dark'
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        $color = $this->status_color;
        return "<span class='badge bg-{$color}'>{$this->status_pesanan}</span>";
    }

    // Quarantine methods
    public function startQuarantine(): void
    {
        $startDate = now();
        $endDate = $startDate->copy()->addDays(7);

        $this->update([
            'status_pesanan' => 'Karantina',
            'karantina_mulai' => $startDate,
            'karantina_selesai' => $endDate,
            'is_karantina_active' => true
        ]);

        // Create quarantine log
        $this->quarantineLog()->create([
            'started_at' => $startDate,
            'scheduled_end_at' => $endDate,
            'status' => 'active',
            'notes' => 'Karantina 7 hari dimulai untuk memastikan kesehatan ikan'
        ]);

        // Add timeline entry
        $this->addTimelineEntry('Karantina', 'Karantina Dimulai',
            'Ikan memasuki periode karantina 7 hari untuk memastikan kesehatan');
    }

    public function completeQuarantine(): void
    {
        $this->update([
            'status_pesanan' => 'Dikirim',
            'is_karantina_active' => false
        ]);

        $this->quarantineLog()->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        $this->addTimelineEntry('Dikirim', 'Karantina Selesai',
            'Periode karantina telah selesai, pesanan siap dikirim');
    }

    // Timeline management
    public function addTimelineEntry(string $status, string $title, string $description, array $metadata = [], bool $isCustomerVisible = true): void
    {
        $this->timeline()->create([
            'status' => $status,
            'title' => $title,
            'description' => $description,
            'metadata' => $metadata,
            'is_customer_visible' => $isCustomerVisible,
            'created_by' => \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::id() : $this->user_id
        ]);
    }

    // Refund methods
    public function canRequestRefund(): bool
    {
        return in_array($this->status_pesanan, ['Dikirim', 'Selesai']) &&
               $this->status_refund === 'none' &&
               !$this->pengembalian()->where('status_pengembalian', 'Menunggu Review')->exists();
    }

    // Use pengembalian model for all refund/return functionality
    /* public function requestRefund(array $data): RefundRequest
    {
        $refund = $this->refundRequests()->create($data);

        $this->update([
            'status_refund' => 'requested',
            'tanggal_refund_request' => now()
        ]);

        // Create timeline entry with user ID explicitly provided
        $this->timeline()->create([
            'status' => 'Refund Requested',
            'title' => 'Refund Diminta',
            'description' => 'Customer mengajukan permintaan refund: ' . $data['deskripsi_masalah'],
            'metadata' => [],
            'is_customer_visible' => true,
            'created_by' => \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::id() : $this->user_id
        ]);

        return $refund;
    } */

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
               !$this->refundRequests()->exists() &&
               $this->tanggal_diterima &&
               $this->tanggal_diterima->copy()->addHours(24)->isFuture();
    }

    public function getUlasanAttribute()
    {
        // Get all reviews for products in this order
        $productIds = $this->detailPesanan->pluck('id_Produk');
        return \App\Models\Ulasan::where('user_id', $this->user_id)
            ->whereIn('id_Produk', $productIds)
            ->get();
    }

    public function getReviewableProductsAttribute()
    {
        // Get all product IDs from this order that haven't been reviewed yet
        $productIds = $this->detailPesanan->pluck('id_Produk')->toArray();

        // Get already reviewed product IDs
        $reviewedProductIds = \App\Models\Ulasan::where('user_id', $this->user_id)
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
        // 2. Pesanan telah diterima
        // 3. Masih ada produk yang belum direview
        return $this->status_pesanan === 'Selesai' &&
               $this->tanggal_diterima &&
               $this->reviewable_products->isNotEmpty();
    }
}
