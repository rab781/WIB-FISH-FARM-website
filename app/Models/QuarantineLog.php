<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class QuarantineLog extends Model
{
    protected $fillable = [
        'id_pesanan',
        'started_at',
        'scheduled_end_at',
        'completed_at',
        'status',
        'notes',
        'daily_checks'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'scheduled_end_at' => 'datetime',
        'completed_at' => 'datetime',
        'daily_checks' => 'array',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function getProgressPercentageAttribute(): int
    {
        if ($this->status === 'completed') {
            return 100;
        }

        if ($this->status === 'cancelled') {
            return 0;
        }

        $totalDays = 7; // 7 days quarantine period
        $elapsedDays = Carbon::now()->diffInDays($this->started_at);

        if ($elapsedDays >= $totalDays) {
            return 100;
        }

        return (int) (($elapsedDays / $totalDays) * 100);
    }

    public function getDaysRemainingAttribute(): int
    {
        if ($this->status !== 'active') {
            return 0;
        }

        $remainingDays = $this->scheduled_end_at->diffInDays(Carbon::now());
        return max(0, $remainingDays);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => '<span class="badge bg-warning text-dark">Karantina Aktif</span>',
            'completed' => '<span class="badge bg-success">Karantina Selesai</span>',
            'cancelled' => '<span class="badge bg-danger">Karantina Dibatalkan</span>',
            default => '<span class="badge bg-secondary">Unknown</span>'
        };
    }

    public function addDailyCheck(array $checkData): void
    {
        $checks = $this->daily_checks ?? [];
        $today = Carbon::now()->format('Y-m-d');

        $checks[$today] = array_merge($checkData, [
            'timestamp' => Carbon::now()->toISOString(),
            'day' => count($checks) + 1
        ]);

        $this->update(['daily_checks' => $checks]);
    }

    public function getTodayCheckAttribute(): ?array
    {
        $today = Carbon::now()->format('Y-m-d');
        return $this->daily_checks[$today] ?? null;
    }

    public function canCompleteQuarantine(): bool
    {
        return $this->status === 'active' &&
               Carbon::now()->gte($this->scheduled_end_at) &&
               $this->daily_checks &&
               count($this->daily_checks) >= 7;
    }
}
