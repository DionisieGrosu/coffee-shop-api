<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Size extends Model
{
    use HasFactory;

    /**
     * Get the coffe.
     */
    public function coffee(): BelongsTo
    {
        return $this->belongsTo(Coffee::class);
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
        'price',
        'is_active',
        'sorder',
        'coffee_id',
    ];
}
