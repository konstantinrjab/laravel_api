<?php

namespace App\Http\Structures;

class Category extends Structure
{
    const KEY_ONE = 'category';
    const KEY_MANY = 'categories';

    public static function getKeyOne()
    {
        return self::KEY_ONE;
    }

    public static function getKeyMany()
    {
        return self::KEY_MANY;
    }

    public static function getOne($category, $items = false)
    {
        $key = self::getKeyOne();

        $structure = [
            $key => [
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

    private static function _addItemsCount($category, &$structure)
    {
        if ($category->items) {
            $count = count($category->items);
        } else {
            $count = 0;
        }
        $structure['category']['items_count'] = $count;
    }
}
