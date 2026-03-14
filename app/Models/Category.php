<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function scopeIsParent($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeIsChild($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function jobOffers(): HasMany
    {
        return $this->hasMany(JobOffer::class);
    }
}
