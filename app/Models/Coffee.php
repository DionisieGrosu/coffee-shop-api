<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coffee extends Model
{
    use HasFactory;

    /**
     * Get the category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the sizes.
     */
    public function sizes(): HasMany
    {
        return $this->HasMany(Size::class);
    }

    /**
     * Get the sizes.
     */
    public function activeSizes(): HasMany
    {
        return $this->HasMany(Size::class)->where('is_active', 1)->orderBy('sorder', 'asc');
    }

    /**
     * Get the reviews.
     */
    public function reviews(): HasMany
    {
        return $this->HasMany(Review::class);
    }

    public function scopeActive($query)
    {
        return $query->has('sizes')->where('is_active', 1);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'img',
        'category_id',
        'topics',
        'is_active',
        'sorder',
    ];
}
