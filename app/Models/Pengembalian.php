<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian';
    protected $primaryKey = 'id_pengembalian';

    protected $fillable = [
        'id_pesanan',
        'user_id',
        'jenis_keluhan',
        'deskripsi_masalah',
        'foto_bukti',
        'jumlah_klaim',
        'metode_refund',
        'nama_bank',
        'nomor_rekening',
        'nama_pemilik_rekening',
        'nama_ewallet',
        'nomor_ewallet',
        'nama_pemilik_ewallet',
        'status_pengembalian',
        'catatan_admin',
        'reviewed_by',
        'tanggal_review',
        'tanggal_pengembalian_dana',
        'nomor_transaksi_pengembalian',
    ];

    protected $casts = [
        'foto_bukti' => 'array',
        'tanggal_review' => 'datetime',
        'tanggal_pengembalian_dana' => 'datetime',
        'jumlah_klaim' => 'decimal:2',
    ];

    // Relationships
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status_pengembalian', 'Menunggu Review');
    }

    public function scopeInReview($query)
    {
        return $query->where('status_pengembalian', 'Dalam Review');
    }

    public function scopeApproved($query)
    {
        return $query->where('status_pengembalian', 'Disetujui');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status_pengembalian', 'Selesai');
    }

    // Accessors
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status_pengembalian) {
            'Menunggu Review' => 'bg-yellow-100 text-yellow-800',
            'Dalam Review' => 'bg-blue-100 text-blue-800',
            'Disetujui' => 'bg-green-100 text-green-800',
            'Ditolak' => 'bg-red-100 text-red-800',
            'Dana Dikembalikan' => 'bg-purple-100 text-purple-800',
            'Selesai' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getFormattedJumlahKlaimAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_klaim, 0, ',', '.');
    }
}
