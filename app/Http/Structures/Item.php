<?php

namespace App\Http\Structures;

use Illuminate\Http\Resources\Json\JsonResource;

class Item extends JsonResource
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
            'item' => $item
        ];
    }
}
