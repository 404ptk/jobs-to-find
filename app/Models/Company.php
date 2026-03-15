<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'logo_path',
        'description',
        'location_id',
        'founded_at',
        'nip',
    ];

    protected $casts = [
        'founded_at' => 'integer',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobOffers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(JobOffer::class);
    }

    public function location(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
