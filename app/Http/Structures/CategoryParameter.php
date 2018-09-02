<?php

namespace App\Http\Structures;


class CategoryParameter extends Structure
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
                'category_id' => $parameter->category_id,
                'parameter_id' => $parameter->parameter_id,
                'created_at' => $parameter->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $parameter->updated_at->format('Y-m-d H:i:s'),
            ],
        ];
    }
}