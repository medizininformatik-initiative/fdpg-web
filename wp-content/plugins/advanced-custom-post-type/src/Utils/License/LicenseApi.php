<?php

namespace ACPT\Utils\License;

use ACPT\Admin\ACPT_License_Manager;

class LicenseApi
{
    /**
     * @param string $url
     * @param array  $data
     *
     * @return mixed
     */
    public static function call($url, $data = [])
    {
        $finalUrl =  self::getApiRoot() . $url;

        $ch = curl_init($finalUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $result = curl_exec($ch);
        curl_close($ch);

        if($result === null){
            return null;
        }

        return json_decode($result, true);
    }

    /**
     * @return string
     */
    public static function downloadLink()
    {
        $license = ACPT_License_Manager::getLicense();
        $userEmail = $license['user_email'];
        $userid = $license['user_id'];
        $license  = $license['license'];

        return sprintf( self::getDownloadApiRoot().'/plugin/download/%s/%d', $license, $userid);
    }

    /**
     * @return string
     */
    private static function getDownloadApiRoot()
    {
        $siteUrl = get_bloginfo('url');

        if($siteUrl === 'http://localhost:8000' or $siteUrl === 'http://localhost:83'){
            return 'http://localhost:83/wp-json/api/v1';
        }

        return 'https://acpt.io/wp-json/api/v1';
    }

    /**
     * @return string
     */
    private static function getApiRoot()
    {
        $siteUrl = get_bloginfo('url');

        // new DOCKER
        if($siteUrl === 'http://localhost:8000' or $siteUrl === 'http://127.0.0.1:8000'){
            return 'http://php-apache/wp-json/api/v1';
        }

        // old DOCKER
        if($siteUrl === 'http://localhost:83' or $siteUrl === 'http://127.0.0.1:83'){
            return 'http://wordpress/wp-json/api/v1';
        }

        return 'https://acpt.io/wp-json/api/v1';
    }
}
