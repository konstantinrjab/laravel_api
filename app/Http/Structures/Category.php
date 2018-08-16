<?php

namespace App\Http\Structures;

class Category
{
    public static function getCategoryStructure($category, $count, $items = null)
    {
        $structure = [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'created_at' => $category->created_at,
                'updated_at' => $category->created_at,
                'items_count' => $count,
            ],
        ];
        if ($items) {
            $structure['category']['items'] = $items;
        }

        return $structure;
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
