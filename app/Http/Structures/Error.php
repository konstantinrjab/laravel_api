<?php

namespace App\Http\Structures;

class Error
{
    public static function getStructure($message, $response = false)
    {
        $structure = [
            'error' => $message,
        ];
        if($response){
            $structure['response'] = $response;
        }

        return $structure;
    }
}
