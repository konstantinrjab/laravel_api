<?php

namespace App\Http\Resources;

class Category
{
    public static function getStructure($category)
    {
        return [
            'category' => $category,
            'items' => $category->items
        ];
    }
    
    public static function getStructureWithItems($category){
        return [
            'category' => $category,
            'items' => $category->items
        ];
    }
}
