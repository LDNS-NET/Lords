<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    protected $fillable = [
        'name',
        'location',
        'number_of_units',
        'description',
    ];

    /**
     * Get the renters for the apartment
     */
    public function renters()
    {
        return $this->hasMany(Renter::class);
    }
}
