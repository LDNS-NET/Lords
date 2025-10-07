<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Renter extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'id_number',
        'apartment_id',
        'house_number',
        'move_in_date',
        'user_id',
    ];

    protected $casts = [
        'move_in_date' => 'date',
    ];

    /**
     * Get the apartment that the renter belongs to
     */
    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    /**
     * Get the user account for the renter
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payments for the renter
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
