<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'image',
        'icon',
        'service_fee',
        'govt_fee',
        'delivery_time',
        'required_documents',
        'is_online_orderable',
        'is_featured',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'status' => 'boolean',
        'is_online_orderable' => 'boolean',
        'service_fee' => 'decimal:2',
        'govt_fee' => 'decimal:2',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function orders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function usefulLinks(): HasMany
    {
        return $this->hasMany(UsefulLink::class);
    }

    public function getTotalFeeAttribute(): float
    {
        return (float) ($this->service_fee + $this->govt_fee);
    }
}
