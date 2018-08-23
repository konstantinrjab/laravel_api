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
            'parameter' => [
                'id' => $parameter->id,
                'category_id' => $parameter->category_id,
                'parameter_id' => $parameter->parameter_id,
                'created_at' => $parameter->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $parameter->updated_at->format('Y-m-d H:i:s'),
            ],
        ];
    }
    public static function getParametersStructure($parameters)
    {
        $structure = [];

        foreach ($parameters as $parameter){
            $structure['parameters'][] = self::getParameterStructure($parameter);
        }

        return $structure;
    }
}