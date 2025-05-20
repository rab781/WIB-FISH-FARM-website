<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ongkir extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan
    protected $table = 'ongkir';

    // Primary key
    protected $primaryKey = 'id_ongkir';

    // Atribut yang dapat diisi
    protected $fillable = [
        'kabupaten_id',
        'biaya',
        'keterangan',
    ];

    /**
     * Relasi dengan kabupaten
     */
    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id', 'id');
    }
}
