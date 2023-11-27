<?php

namespace ACPT\Utils\PHP;

class Url
{
    public static function host()
    {
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        }
        else{
            $protocol = 'http';
        }

        return $protocol . "://" . $_SERVER['HTTP_HOST'];
    }

    public static function baseUri($queryString = [])
    {
        $baseUri = str_replace( '?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI'] );

        $uri = self::host() . $baseUri;

        if(!empty($queryString)){
            $uri = $uri . '?' . http_build_query($queryString);
        }

        return $uri;
    }
}
