<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


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
        'created_by',
    ];

    protected $casts = [
        'move_in_date' => 'date',
    ];
    
    protected static function booted()
    {
        // Global scope to show only the current user's records
        static::addGlobalScope('created_by', function ($query) {
            if (Auth::check()) {
                $query->where('created_by', Auth::id());
            }
        });

        // Automatically set created_by on create
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });
    }
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
