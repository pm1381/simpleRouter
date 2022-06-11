<?php 

namespace Bramus\Core;

class Tools
{
    public static function showQuery($string, $checkDie = false)
    {
        echo $string;
        if ($checkDie){
            die();
        }
    }
}
