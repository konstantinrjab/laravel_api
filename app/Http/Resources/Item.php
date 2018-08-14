<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Item extends JsonResource
{
    public static function getStructure($item)
    {
        if($item->parameters){
            foreach ($item->parameters as $parameter) {
                $parameters[] = [
                    'id' => $parameter->id,
                    'name' => $parameter->name,
                    'value' => $parameter->pivot->value,
                ];
            }
        }
        
        return [
            'item' => [
                'id' => $item->id,
                'name' => $item->name,
                'category' => $item->category,
                'parameters' => (isset($parameters) ? $parameters : null),
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]
        ];
    }
}
