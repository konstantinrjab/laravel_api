<?php

namespace App\Http\Resources;

class Category
{
    public static function getCategoryStructure($category)
    {
        return [
            'category' => $category
        ];
    }
    public static function getCategoriesStructure($categories, $count)
    {
        return [
            'category' => [
                'count' => $count,
                'categories' => $categories,
            ]
        ];
    }
}
