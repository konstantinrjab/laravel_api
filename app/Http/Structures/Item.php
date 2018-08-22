<?php

namespace App\Http\Structures;

class Item
{
    public static function getItemsStructure($items)
    {
        $structure = [];
        foreach ($items as $item) {
            $structure['items'][] = self::getItemStructure($item);
        }

        return $structure;
    }

    public static function getItemStructure($item, $parameters = false)
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
            ]
        ];
        if($parameters){
            $structure['item']['parameters'] = $item->parameters;
        }

        return $structure;
    }
}
