<?php

namespace App\Http\Structures;

class Error
{
    public static function getStructure($message)
    {
        return [
            'error' => $message,
        ];
    }
}
