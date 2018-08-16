<?php
/**
 * Created by PhpStorm.
 * User: konstantin
 * Date: 08/16/18
 * Time: 10:41 PM
 */

namespace App\Http\Structures;


class CategoryParameter
{
    public static function getParameterStructure($parameter)
    {
        return [
            'parameter' => $parameter,
        ];
    }
    public static function getParametersStructure($parameter)
    {
        return [
            'parameters' => $parameter,
        ];
    }
}