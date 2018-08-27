<?php

namespace App\Http\Structures;

class Error
{
    public static function getStructure($message)
    {
        $structure = [
            'error' => $message,
        ];

        return $structure;
    }
}
