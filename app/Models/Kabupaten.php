<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kabupaten extends Model
{
    protected $table = 'kabupaten';
    protected $fillable = ['provinsi_id', 'nama_kabupaten'];

    /**
     * Get the provinsi that owns the kabupaten
     */
    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class);
    }

    /**
     * Get all kecamatan for this kabupaten
     */
    public function kecamatan(): HasMany
    {
        return $this->hasMany(Kecamatan::class);
    }
}
