<?php

namespace App\Enums;

enum UserTier: string
{
    case SILVER = 'silver';
    case GOLD = 'gold';
    case PLATINUM = 'platinum';
}