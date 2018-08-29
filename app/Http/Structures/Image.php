<?php
/**
 * Created by PhpStorm.
 * User: konstantin
 * Date: 08/16/18
 * Time: 10:41 PM
 */

namespace App\Http\Structures;

class Image extends Structure
{
    const KEY_ONE = 'image';
    const KEY_MANY = 'images';

    public static function getKeyOne()
    {
        return self::KEY_ONE;
    }

    public static function getKeyMany()
    {
        return self::KEY_MANY;
    }

    public static function getOne($image)
    {
        $structure = [
                'id' => $image->id,
                'item_id' => $image->item_id,
                'order' => $image->order,
                'path' => $image->path,
                'created_at' => $image->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $image->updated_at->format('Y-m-d H:i:s'),
        ];

        return $structure;
    }
}