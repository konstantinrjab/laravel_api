<?php

namespace App\Http\Resources;

class Category
{
    public static function getStructure($category)
    {
        return [
            'category' => $category,
//            'items' => \App\Item::where('category_id', $category->id)
        ];
    }
    
    public static function getStructureWithItems($category){
        return [
            'category' => $category,
            'items' => $category->items
        ];
    }
}
