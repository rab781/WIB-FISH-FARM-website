<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderTimeline extends Model
{
    protected $fillable = [
        'id_pesanan',
        'status',
        'title',
        'description',
        'metadata',
        'is_customer_visible',
        'created_by'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_customer_visible' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Menunggu Pembayaran' => 'warning',
            'Pembayaran Dikonfirmasi' => 'info',
            'Diproses' => 'orange',
            'Karantina' => 'purple',
            'Dikirim' => 'primary',
            'Selesai' => 'success',
            'Dibatalkan' => 'danger',
            'Refund Requested', 'Refund Approved', 'Refund Rejected', 'Refund Processed' => 'secondary',
            default => 'dark'
        };
    }

    public function getIconAttribute(): string
    {
        return match($this->status) {
            'Menunggu Pembayaran' => 'fas fa-clock',
            'Pembayaran Dikonfirmasi' => 'fas fa-check-circle',
            'Diproses' => 'fas fa-cogs',
            'Karantina' => 'fas fa-shield-alt',
            'Dikirim' => 'fas fa-truck',
            'Selesai' => 'fas fa-flag-checkered',
            'Dibatalkan' => 'fas fa-times-circle',
            'Refund Requested' => 'fas fa-undo',
            'Refund Approved' => 'fas fa-check',
            'Refund Rejected' => 'fas fa-times',
            'Refund Processed' => 'fas fa-money-bill-wave',
            default => 'fas fa-circle'
        };
    }
}
