<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alamat extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'alamat';

    // Use RajaOngkir ID as the primary key
    protected $primaryKey = 'id';

    // Disable auto-incrementing as we'll use Raja Ongkir's ID
    public $incrementing = false;

    // Fillable attributes
    protected $fillable = [
        'id', // This will store the raja_ongkir_id directly
        'provinsi',
        'kabupaten',
        'kecamatan',
        'tipe',
        'kode_pos'
    ];

    /**
     * Get all users with this address
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'alamat_id');
    }

    /**
     * Get all ongkir for this address
     */
    public function ongkir(): HasMany
    {
        return $this->hasMany(Ongkir::class, 'alamat_id');
    }
}
