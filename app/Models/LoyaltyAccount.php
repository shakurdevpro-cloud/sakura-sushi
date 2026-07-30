<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyAccount extends Model
{
    protected $fillable = ['user_id', 'points', 'tier'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
