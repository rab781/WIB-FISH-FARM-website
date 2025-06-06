<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;
    protected $table = 'expenses';

    protected $fillable = [
        'category',
        'description',
        'amount',
        'expense_date',
        'notes'
    ];

    protected $dates = [
        'expense_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];
}
