<?php

namespace App\Http\Structures;

/** @SWG\Definition(
 *   definition="error",
 *   @SWG\Property(
 *      property="message",
 *      type="string",
 *      description="Category ID"
 *   ),
 * )
 */
class Error
{
    public static function getStructure($message, $code = 400)
    {
        $structure = [
            'error' => $message,
        ];

        return response()->json($structure, $code);
    }
}
