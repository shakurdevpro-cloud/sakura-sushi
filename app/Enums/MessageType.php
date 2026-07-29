<?php

namespace App\Enums;

enum MessageType: string
{
    case GENERAL = 'general';
    case RESERVATION = 'reservation';
    case EVENT = 'event';
    case CATERING = 'catering';
}