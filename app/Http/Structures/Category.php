<?php

namespace App\Http\Structures;

class Category
{
    public static function getCategoryStructure($category, $items = null)
    {
        $structure = [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'created_at' => $category->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $category->updated_at->format('Y-m-d H:i:s'),
            ],
        ];
        self::_addItemsCount($category, $structure);

        if ($items) {
            $structure['category']['items'] = $items;
        }

        return $structure;
    }

    public static function getCategoriesStructure($categories)
    {
        return [
            'categories' => [
                'categories_count' => count($categories),
                'categories' => $categories,
            ]
        ];
    }

    private static function _addItemsCount($category, &$structure){
        if($category->items){
            $count = count($category->items);
        } else {
            $count = 0;
        }
        $structure['category']['items_count'] = $count;
    }
}
