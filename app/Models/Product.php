<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Support\Number;

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


    public function scopeActive() 
    {
        return $this->query()->where('is_active', 1);
    }

    public function getFormatedPriceAttribute()
    {
        return Number::currency($this->price, 'SDG', precision: 0);
    }

}
