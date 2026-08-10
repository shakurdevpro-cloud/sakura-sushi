<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'name', 'token', 'confirmed_at', 'unsubscribed_at', 'source'];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    public function scopeConfirmed($query)
    {
        return $query->whereNotNull('confirmed_at')->whereNull('unsubscribed_at');
    }
}
