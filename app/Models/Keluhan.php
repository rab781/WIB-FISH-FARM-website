<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keluhan extends Model
{
    protected $table = 'keluhans';

    protected $fillable = [
        'user_id',
        'jenis_keluhan',
        'keluhan',
        'gambar',
        'status',
        'respon_admin',
        'respon_at',
    ];

    protected $casts = [
        'respon_at' => 'datetime',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
