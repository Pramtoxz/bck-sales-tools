<?php

namespace App\Helper;

class Helper
{
    public static function getKodeUniqueId($kode,$length = 5)
    {
        $string = uniqid(rand());
        $randomString = substr($string, 0, $length);
        $time = date('his');
        return $kode."-".$randomString."-".$time;
    }
}