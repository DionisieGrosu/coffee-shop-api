<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * Get the coffees that belongs to category.
     */
    public function coffees(): HasMany
    {
        return $this->hasMany(Coffee::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'is_active',
        'sorder',
    ];
}
