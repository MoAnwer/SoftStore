<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $guarded = [];


    public function scopeActive()
    {
        return $this->where('is_active', 1);
    }

    public function products() : HasMany
    {
        return $this->hasMany(Product::class);
    }
}
