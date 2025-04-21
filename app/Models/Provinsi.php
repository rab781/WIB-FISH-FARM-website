<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provinsi extends Model
{
    protected $table = 'provinsi';
    protected $fillable = ['nama_provinsi'];

    /**
     * Get all kabupaten for this provinsi
     */
    public function kabupaten(): HasMany
    {
        return $this->hasMany(Kabupaten::class);
    }
}
