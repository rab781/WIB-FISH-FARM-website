<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefundRequest extends Model
{
    protected $fillable = [
        'id_pesanan',
        'jenis_refund',
        'deskripsi_masalah',
        'bukti_pendukung',
        'status',
        'catatan_admin',
        'jumlah_diminta',
        'jumlah_disetujui',
        'metode_refund',
        'detail_refund',
        'reviewed_at',
        'processed_at',
        'reviewed_by'
    ];

    protected $casts = [
        'bukti_pendukung' => 'array',
        'jumlah_diminta' => 'decimal:2',
        'jumlah_disetujui' => 'decimal:2',
        'reviewed_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-warning text-dark">Menunggu Review</span>',
            'reviewing' => '<span class="badge bg-info">Sedang Ditinjau</span>',
            'approved' => '<span class="badge bg-success">Disetujui</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
            'processed' => '<span class="badge bg-primary">Diproses</span>',
            default => '<span class="badge bg-secondary">Unknown</span>'
        };
    }

    public function getJenisRefundTextAttribute(): string
    {
        return match($this->jenis_refund) {
            'kerusakan' => 'Produk Rusak',
            'keterlambatan' => 'Keterlambatan Pengiriman',
            'tidak_sesuai' => 'Tidak Sesuai Pesanan',
            'kematian_ikan' => 'Kematian Ikan',
            'lainnya' => 'Lainnya',
            default => $this->jenis_refund
        };
    }
}
