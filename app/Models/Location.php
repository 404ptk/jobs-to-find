<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = [
        'city',
        'country',
        'region',
    ];

    public function jobOffers(): HasMany
    {
        return $this->hasMany(JobOffer::class);
    }
}
