<?php

namespace ACPT\Admin;

use ACPT\Utils\License\LicenseApi;
use ACPT\Utils\PHP\IP;

class ACPT_License_Manager
{
    const PRIVATE_KEY_NAME = 'acpt_license_active';

    /**
     * @param array $postData
     * @return bool
     * @throws \Exception
     */
    public static function activate(array $postData = [])
    {
        if(wp_verify_nonce($postData['activation'])){
            return false;
        }

        try {
            $code = sanitize_text_field($postData['code']);
            $email = sanitize_email($postData['email']);
            $siteName = sanitize_text_field($postData['siteName']);
            $siteUrl = sanitize_url($postData['siteUrl']);
            $ip = IP::getClientIP();

            $activate = LicenseApi::call('/license/activate', [
                'license' => $code,
                'email' => $email,
                'siteName' => $siteName,
                'siteUrl' => $siteUrl,
                'ip' => $ip,
            ]);

            if(isset($activate['error'])){
                return false;
            }

            if(!isset($activate['id'])){
                return false;
            }

            $siteName = get_bloginfo('name');
            $siteUrl = get_bloginfo('url');

            return ACPT_Key_Value_Storage::set(self::PRIVATE_KEY_NAME, [
                'activation_id' => $activate['id'],
                'license' => md5($code),
                'site_name' => $siteName,
                'site_url' => $siteUrl,
                'user_email' => $activate['user_email'],
                'user_id' => $activate['user_id'],
                'ip' => $ip,
            ]);
        } catch (\Exception $exception){
            return false;
        }
    }

    /**
     * @return bool
     */
    public static function destroy()
    {
        $licenseActivation = ACPT_License_Manager::getLicense();
        $deactivation = LicenseApi::call('/license/deactivate', [
            'id' => $licenseActivation['activation_id']
        ]);

        if(!isset($deactivation['id'])){
            return false;
        }

        return ACPT_Key_Value_Storage::delete(self::PRIVATE_KEY_NAME);
    }

    /**
     * @return mixed
     */
    public static function getLicense()
    {
        return ACPT_Key_Value_Storage::get(self::PRIVATE_KEY_NAME);
    }

    /**
     * @return bool
     */
    public static function isLicenseValid()
    {
        $storedKey = self::getLicense();

        if(empty($storedKey)){
            return false;
        }

        if(!is_array($storedKey)){
            return false;
        }

        if(!isset($storedKey['activation_id'])){
            return false;
        }

        if(!isset($storedKey['site_name'])){
            return false;
        }

        if(!isset($storedKey['site_url'])){
            return false;
        }

        if(!isset($storedKey['license'])){
            return false;
        }

        if(!isset($storedKey['user_email'])){
            return false;
        }

        if(!isset($storedKey['user_id'])){
            return false;
        }

        return true;
    }
}