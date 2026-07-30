<?php

namespace App\Exceptions;

use Exception;

class SlotUnavailableException extends Exception
{
    protected $message = 'Ce créneau est complet, merci de choisir un autre horaire.';

    public function render($request)
    {
        return response()->json(['message' => $this->getMessage()], 422);
    }
}