<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $guarded = [];

    public function products() : HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive()
    {
        return $this->query()->where('is_active', 1);
    }
}
