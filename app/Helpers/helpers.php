<?php

use App\Models\Reservation;

if (! function_exists('price_format')) {
    function price_format(int $cents): string
    {
        return '$' . number_format($cents / 100, 2);
    }
}

if (! function_exists('generate_reference')) {
    function generate_reference(string $prefix): string
    {
        do {
            $reference = $prefix
                . '-' . date('Y')
                . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Reservation::where('reference', $reference)->exists());

        return $reference;
    }
}
