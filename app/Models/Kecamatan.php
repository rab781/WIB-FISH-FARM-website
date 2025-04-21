<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    protected $table = 'kecamatan';
    protected $fillable = ['kabupaten_id', 'nama_kecamatan'];

    /**
     * Get the kabupaten that owns the kecamatan
     */
    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class);
    }

    /**
     * Get all users in this kecamatan
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
