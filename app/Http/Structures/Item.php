<?php

namespace App\Http\Structures;

class Item extends Structure
{
    const KEY_ONE = 'item';
    const KEY_MANY = 'items';

    public static function getKeyOne()
    {
        return self::KEY_ONE;
    }

    public static function getKeyMany()
    {
        return self::KEY_MANY;
    }

    public static function getOne($item, $parameters = false)
    {
        $structure = [
            'item' => [
                'id' => $item->id,
                'name' => $item->name,
                'sku' => $item->sku,
                'image' => $item->image,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $item->updated_at->format('Y-m-d H:i:s'),
                'category' => $item->category,
                'price' => $item->price,
            ]
        ];
        if ($parameters) {
            $structure['item']['parameters'] = $item->parameters;
        }

        return $structure;
    }
}
