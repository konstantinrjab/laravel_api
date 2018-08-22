<?php

namespace App\Http\Structures;

class Item
{
    public static function getItemsStructure($items)
    {
        return [
            'items' => $items
        ];
    }

    public static function getItemStructure($item)
    {
        return [
            'item' => [
                'id' => $item->id,
                'name' => $item->name,
                'image' => $item->image,
                'created_at' => $item->created_at->format('Y-m-d'),
                'updated_at' => $item->updated_at->format('Y-m-d'),
                'category' => $item->category,
                'parameters' => $item->parameters,
            ]
        ];
    }
}
