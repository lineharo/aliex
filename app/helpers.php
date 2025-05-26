<?php

if (! function_exists('getUserIpAddr')) {
    function getUserIpAddr(){
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}

if (! function_exists('clearHTML')) {
    function clearHTML( $html_code )
    {
        $result = preg_replace('~<style[^<]*<\/style>~mi', '', $html_code);
        $result = preg_replace('~<\/?(?:div|br|p)[^>]*>~', "\n", $result);
        $result = strip_tags( $result );
        $result = html_entity_decode( $result );
        $result = trim( $result );
        return $result;
    }
}

if (! function_exists('lhTranslit')) {
    function lhTranslit($string, $maxLength = 0)
    {
        $converter = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ь' => '', 'ы' => 'y', 'ъ' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        ];

        $slug = mb_strtolower($string);
        $slug = trim($slug);
        $slug = strtr($slug, $converter);
        $slug = preg_replace('~[^A-Za-z0-9-]+~', '-', $slug);
        $slug = preg_replace('~^[\-]+|[\-]$~', '', $slug);

        if ($maxLength > 0) {
            $slug = mb_substr($slug, 0, $maxLength);
        }

        return $slug;
    }
}