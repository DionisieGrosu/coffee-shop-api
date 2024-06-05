<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    /**
     * Get the coffee.
     */
    public function coffee(): BelongsTo
    {
        return $this->BelongsTo(Coffee::class);
    }

    /**
     * Get the user.
     */
    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    /**
     * Get the size.
     */
    public function size(): BelongsTo
    {
        return $this->BelongsTo(Size::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'coffee_id',
        'user_id',
        'size_id',
        'qt',
    ];
}
