<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function address() : HasOne
    {
        return $this->hasOne(Address::class);
    }
}
