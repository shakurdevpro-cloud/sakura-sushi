<?php

namespace App\Enums;

enum PromotionType: string
{
    case PERCENTAGE = 'percentage';
    case FIXED = 'fixed';
    case FREE_DELIVERY = 'free_delivery';
}   