<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'reference',
        'location',
        'date',
        'time',
        'guests',
        'seating_preference',
        'status',
        'first_name',
        'last_name',
        'email',
        'phone',
        'special_requests',
        'occasion',
        'sms_consent',
        'sms_reminder_sent',
        'confirmed_at',
        'cancelled_at',
        'cancel_reason',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'status' => ReservationStatus::class,
        'sms_consent' => 'boolean',
        'sms_reminder_sent' => 'boolean',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Combine date + time en un Carbon exploitable (ex: pour planifier le rappel SMS)
    public function getScheduledAtAttribute(): Carbon
    {
        $time = $this->getRawOriginal('time');

        return Carbon::parse($this->date->format('Y-m-d') . ' ' . $time);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->toDateString());
    }
}
