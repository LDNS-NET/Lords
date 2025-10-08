<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Apartment extends Model
{
    protected $fillable = [
        'name',
        'location',
        'number_of_units',
        'description',
        'created_by',
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
     * Get the renters for the apartment
     */
    public function renters()
    {
        return $this->hasMany(Renter::class);
    }
}
