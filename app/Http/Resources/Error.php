<?php

namespace App\Http\Resources;

class Error
{
    public static function getStructure($message)
    {
        return [
            'error' => $message,
        ];
    }
}
