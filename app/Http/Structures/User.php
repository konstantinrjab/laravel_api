<?php

namespace App\Http\Structures;

class User
{
    const KEY_ONE = 'user';
//    const KEY_MANY = 'users';

    public static function getKeyOne()
    {
        return self::KEY_ONE;
    }

//    public static function getKeyMany()
//    {
//        return self::KEY_MANY;
//    }

    public static function getOne($user)
    {
        $structure = [
            self::getKeyOne() => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
                'api_token' => $user->api_token,
            ]
        ];

        return $structure;
    }
}
