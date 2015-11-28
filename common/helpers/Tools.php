<?php

namespace common\helpers;

class Tools
{
    public static function getCleanUrl($string)
    {
        $cleanUrl = str_ireplace(['ę', 'ó', 'ą', 'ś', 'ł', 'ż', 'ź', 'ć', 'ń'], ['e', 'o', 'a', 's', 'l', 'z', 'z', 'c', 'n'], $string);
        $cleanUrl = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $cleanUrl);
        $cleanUrl = strtolower(trim($cleanUrl, '-'));
        $cleanUrl = preg_replace("/[\/_|+ -]+/", '-', $cleanUrl);

        return $cleanUrl;
    }

    public static function cleanContent($text)
    {
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('Core.RemoveInvalidImg',true);
        $config->set('Attr.DefaultImageAlt','');

        \HTMLPurifier_URISchemeRegistry::instance()->register('data', new \HTMLPurifier_URIScheme_data());

        $purifier = new \HTMLPurifier($config);

        return $purifier->purify($text);
    }
}