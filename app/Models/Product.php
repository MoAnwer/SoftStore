<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Product extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'images'  => 'array'
    ];

    public function brand() : BelongsTo
    {
        return $this->belongsTo(Brand::class)->withDefault();
    }

    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class)->withDefault();
    }

    public function orderItem() : HasMany
    {
        return $this->hasMany(OrderItem::class)->withDefault();
    }

}
