<?php

namespace App\Http\Structures;

class User
{
    const KEY_ONE = 'user';
//    const KEY_MANY = 'users';

    public static function getKeyOne()
    {
        return self::KEY_ONE;
    }

//    public static function getKeyMany()
//    {
//        return self::KEY_MANY;
//    }

    public static function getOne($item, $parameters = false)
    {
        $structure = [
            'item' => [
                'id' => $item->id,
                'name' => $item->name,
                'sku' => $item->sku,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $item->updated_at->format('Y-m-d H:i:s'),
                'category' => $item->category,
                'price' => $item->price,
                'images' => $item->images
            ]
        ];
        if ($parameters) {
            $itemParameters = \App\ItemParameter::where('item_id', $item->id)->get();
            $structure['item'] = array_merge($structure['item'], ItemParameter::getMany($itemParameters));
        }
        return $structure;
    }
}
