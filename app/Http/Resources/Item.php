<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Item extends JsonResource
{
    public function getStructure($item)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'categories' => [
                'category' => [
                    'category_id' => $categoryID,
                    'category_name' => $categoryName,
                ],
            ],
            'parameters' => $this->parameters,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
