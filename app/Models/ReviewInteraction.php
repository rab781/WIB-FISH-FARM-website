<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewInteraction extends Model
{
    protected $fillable = [
        'user_id',
        'ulasan_id',
        'interaction_type'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ulasan(): BelongsTo
    {
        return $this->belongsTo(Ulasan::class, 'ulasan_id', 'id_ulasan');
    }

    public function scopeHelpful($query)
    {
        return $query->where('interaction_type', 'helpful');
    }

    public function scopeNotHelpful($query)
    {
        return $query->where('interaction_type', 'not_helpful');
    }
}
