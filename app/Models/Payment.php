<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'renter_id',
        'renter_name',
        'amount',
        'reference',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the renter that owns the payment
     */
    public function renter()
    {
        return $this->belongsTo(Renter::class);
    }
}
