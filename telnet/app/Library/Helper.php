<?php
namespace App\Library;

class Helper{

    /**
     * 
     *
     * @param [type] $length
     * @return void
     */
    public static function genRandStr($pre=0,$length = 10){
        $sublen = strlen($pre);
        $length = $length - $sublen;
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($str)-1;
        $randstr = '';
        for ($i=0;$i<$length;$i++) {
            $num=mt_rand(0,$len);
            $randstr .= $str[$num];
        }
        return (String)$pre.$randstr;
    }

}