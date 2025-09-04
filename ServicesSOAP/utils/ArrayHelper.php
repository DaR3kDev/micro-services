<?php

namespace App\utils;

class ArrayHelper
{
    public static function toArray($obj): array
    {
        return json_decode(json_encode($obj), true);
    }
}
