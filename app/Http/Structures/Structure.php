<?php

namespace App\Http\Structures;

abstract class Structure
{
    abstract public static function getKeyOne();

    abstract public static function getKeyMany();

    abstract public static function getOne($ident);

    public static function getMany($idents)
    {
        $structure = [];
        $key = static::getKeyMany();

        if (empty($idents{0})) {
            return [$key => ''];
        }
        foreach ($idents as $ident) {
            $structure[$key][] = static::getOne($ident);
        }

        return $structure;
    }
}