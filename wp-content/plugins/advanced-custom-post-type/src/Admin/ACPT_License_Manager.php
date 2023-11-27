<?php

namespace ACPT\Admin;

use ACPT\Utils\Http\ACPTApiClient;
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
            $activation = self::tryToActivate($postData);

            if(isset($activation['error'])){
                return false;
            }

            if(!isset($activation['id'])){
                return false;
            }

	        self::sendInsights($activation['id']);

            $siteName = get_bloginfo('name');
            $siteUrl = get_bloginfo('url');

            return ACPT_Key_Value_Storage::set(self::PRIVATE_KEY_NAME, [
                'activation_id' => $activation['id'],
                'license' => md5($activation['license']),
                'site_name' => $siteName,
                'site_url' => $siteUrl,
                'user_email' => $activation['user_email'],
                'user_id' => $activation['user_id'],
                'ip' => IP::getClientIP(),
            ]);
        } catch (\Exception $exception){
            return false;
        }
    }

	/**
	 * @param $postData
	 *
	 * @return mixed
	 * @throws \Exception
	 */
    private static function tryToActivate($postData)
    {
	    $code = sanitize_text_field($postData['code']);
	    $email = sanitize_email($postData['email']);
	    $siteName = sanitize_text_field($postData['siteName']);
	    $siteUrl = sanitize_url($postData['siteUrl']);
	    $ip = IP::getClientIP();

	    return ACPTApiClient::call('/license/activate', [
		    'license' => $code,
		    'email' => $email,
		    'siteName' => $siteName,
		    'siteUrl' => $siteUrl,
		    'ip' => $ip,
	    ]);
    }

	/**
	 * @param $activationId
	 *
	 * @return mixed
	 * @throws \Exception
	 */
    private static function sendInsights($activationId)
    {
	    global $wp_version;
	    global $wpdb;

	    return ACPTApiClient::call('/license/insights/send', [
		    'id' => $activationId,
		    'insights' => [
			    [
				    'key' => 'WP_VERSION',
				    'value' => $wp_version
			    ],
			    [
				    'key' => 'PHP_VERSION',
				    'value' => phpversion()
			    ],
			    [
				    'key' => 'MYSQL_VERSION',
				    'value' => $wpdb->db_version()
			    ],
			    [
				    'key' => 'PLUGIN_VERSION',
				    'value' => ACPT_PLUGIN_VERSION
			    ],
		    ],
	    ]);
    }

	/**
	 * @return bool
	 * @throws \Exception
	 */
    public static function destroy()
    {
        $licenseActivation = ACPT_License_Manager::getLicense();
        $deactivation = ACPTApiClient::call('/license/deactivate', [
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

	/**
	 * @return string
	 */
	public static function getDownloadLink()
	{
		$license = self::getLicense();
		$userEmail = $license['user_email'];
		$userid = $license['user_id'];
		$license  = $license['license'];

		return sprintf( ACPTApiClient::BASE_ACPT_URL.'/plugin/download/%s/%d', $license, $userid);
	}
}