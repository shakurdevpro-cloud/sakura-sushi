<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'reference', 'status', 'type',
        'subtotal', 'delivery_fee', 'discount', 'total',
        'promo_code_id', 'delivery_address', 'guest_email', 'guest_phone',
        'notes', 'stripe_payment_intent_id', 'paid_at', 'estimated_delivery_at',
        'cancelled_at', 'cancel_reason', 'ip_address',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'delivery_address' => 'array',
        'paid_at' => 'datetime',
        'estimated_delivery_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}