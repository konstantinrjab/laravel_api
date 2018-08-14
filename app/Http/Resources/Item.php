<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Item extends JsonResource
{
    public static function getStructure($item, $category = null, $tags = null)
    {
//        foreach ($tags as $tag) {
//            $tags[] = [
//                '$tag_id' => $tag->id,
//                '$tag_name' => $tag->name,
//            ];
//        }
        
        return [
            'item' => [
                'id' => $item->id,
                'name' => $item->name,
                'category' => $item->category,
                'parameters' => $item->parameters,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                ]
        ];
    }
}
