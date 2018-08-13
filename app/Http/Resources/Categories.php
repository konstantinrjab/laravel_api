<?php

namespace App\Http\Resources;

class Categories
{
    public static function getStructure($categories, $count)
    {
        return [
            'count' => $count,
            'categories' => $categories,
        ];
    }
}
