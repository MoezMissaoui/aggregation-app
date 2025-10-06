<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Get the service offers for the operator.
     */
    public function serviceOffers()
    {
        return $this->hasMany(ServiceOffer::class);
    }

    /**
     * Get the services through service offers.
     */
    public function services()
    {
        return $this->hasManyThrough(Service::class, ServiceOffer::class);
    }

    /**
     * Find operator by slug.
     */
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }
}