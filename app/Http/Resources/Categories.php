<?php

namespace App\Http\Resources;

class Categories
{
    public static function getCategoryStructure($categories, $count)
    {
        return [
            'count' => $count,
            'categories' => $categories,
        ];
    }
}
