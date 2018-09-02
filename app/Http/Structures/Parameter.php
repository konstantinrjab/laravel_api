<?php
/**
 * Created by PhpStorm.
 * User: konstantin
 * Date: 08/16/18
 * Time: 10:41 PM
 */

namespace App\Http\Structures;

class Parameter extends Structure
{
    const KEY_ONE = 'parameter';
    const KEY_MANY = 'parameters';

    public static function getKeyOne()
    {
        return self::KEY_ONE;
    }

    public static function getKeyMany()
    {
        return self::KEY_MANY;
    }

    public static function getOne($parameter)
    {
        return [
            self::getKeyOne() => [
                'id' => $parameter->id,
                'name' => $parameter->name,
                'created_at' => $parameter->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $parameter->updated_at->format('Y-m-d H:i:s'),
            ],
        ];
    }
}