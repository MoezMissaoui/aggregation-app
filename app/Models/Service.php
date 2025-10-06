<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'shortcode',
        'sub_keyword',
        'unsub_keyword',
        'url',
        'partner_id',
        'is_active',
    ];

    /**
     * Get the partner that owns the service.
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Get the service offers for the service.
     */
    public function serviceOffers()
    {
        return $this->hasMany(ServiceOffer::class);
    }

}