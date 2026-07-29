<?php

namespace App\Enums;

enum LoyaltyType: string
{
    case EARNED = 'earned';
    case REDEEMED = 'redeemed';
    case BONUS = 'bonus';
    case EXPIRED = 'expired';
}