<?php
namespace BitApps\AssistPro\Core\Update;

use BitApps\AssistPro\Config;
use BitApps\AssistPro\Core\Helpers\DateTimeHelper;
use BitApps\AssistPro\Core\Http\Client\HttpClient;
use WP_Error;

final class API
{
    public static $httpClient = null;

    public static function getAPiEndPoint()
    {
        return 'https://wp-api.bitapps.pro';
    }

    public static function httpClient()
    {
        if (self::$httpClient === null) {
            self::$httpClient = new HttpClient();
            self::$httpClient->setBaseUri(self::getAPiEndPoint());
        }
        return self::$httpClient;
    }

    public static function getUpdatedInfo()
    {
        $licenseKey = self::getKey();
        $client = self::httpClient();
        $client->setHeaders([
            'licKey' => $licenseKey,
        ]);
        $pluginInfoResponse = $client->get('/update/bit-assist-pro');
        if (is_wp_error($pluginInfoResponse)) {
            return $pluginInfoResponse;
        }
        if (!empty($pluginInfoResponse->status) && $pluginInfoResponse->status == 'expired') {
            self::removeKeyData();
            return new WP_Error('API_ERROR', $pluginInfoResponse->message);
        }
        if (empty($pluginInfoResponse->data)) {
            return new WP_Error('API_ERROR', $pluginInfoResponse->message);
        }
        $pluginData = $pluginInfoResponse->data;
        $dateTimeHelper = new DateTimeHelper();
        $pluginData->updatedAt = $dateTimeHelper->getFormated($pluginData->updatedAt, 'Y-m-d\TH:i:s.u\Z', DateTimeHelper::wp_timezone(), 'Y-m-d H:i:s', null);
        if (!empty($pluginData->details)) {
            $pluginData->sections['description'] = $pluginData->details;
        } else {
            $pluginData->sections['description'] = '';
        }
        if (!empty($pluginData->changelog)) {
            $pluginData->sections['changelog'] = $pluginData->changelog;
        } else {
            $pluginData->sections['changelog'] = '';
        }
        if ($licenseKey) {
            $pluginData->downloadLink = self::getAPiEndPoint() . '/download/' . $licenseKey;
        } else {
            $pluginData->downloadLink = '';
        }
        return $pluginData;
    }

    public static function activateLicense($licenseKey)
    {
        $data['licenseKey'] = $licenseKey;
        $data['domain'] = site_url();
        $data['slug'] = 'bit-assist-pro';
        $client = self::httpClient();
        $client->setHeaders([
            'content-type' => 'application/json',
        ]);
        $client->setBody($data);
        $activateResponse = $client->post('/activate');
        if (!is_wp_error($activateResponse) && $activateResponse->status === 'success') {
            self::setKeyData($licenseKey, $activateResponse);
            return true;
        }
        return empty($activateResponse->message) ? __('Unknown error occurred', 'bit-assist') : $activateResponse->message;
    }

    public static function disconnectLicense()
    {
        $integrateData = Config::getOption('license_data');
        if (!empty($integrateData) && is_array($integrateData) && $integrateData['status'] === 'success') {
            $data['licenseKey'] = $integrateData['key'];
            $data['domain'] = site_url();
            $client = self::httpClient();
            $client->setHeaders([
                'content-type' => 'application/json',
            ]);
            $client->setBody($data);
            $deactivateResponse = $client->post('/deactivate');
            if (!is_wp_error($deactivateResponse) && $deactivateResponse->status === 'success' || $deactivateResponse->code === 'INVALID_LICENSE') {
                self::removeKeyData();
                return true;
            }
            return empty($deactivateResponse->message) ? __('Unknown error occurred', 'bit-assist') : $deactivateResponse->message;
        }
        return __('License data is missing', 'bit-assist');
    }

    public static function setKeyData($licenseKey, $licData)
    {
        $data['key'] = $licenseKey;
        $data['status'] = $licData->status;
        $data['expireIn'] = $licData->expireIn;
        return Config::updateOption('license_data', $data, null);
    }

    public static function getKey()
    {
        $integrateData = Config::getOption('license_data');
        $licenseKey = false;
        if (!empty($integrateData) && is_array($integrateData) && $integrateData['status'] === 'success') {
            $licenseKey = $integrateData['key'];
        }
        return $licenseKey;
    }

    public static function removeKeyData()
    {
        return Config::deleteOption('license_data');
    }

    public static function isLicenseActive()
    {
        $integrateData = Config::getOption('license_data');
        if (!empty($integrateData) && is_array($integrateData) && $integrateData['status'] === 'success') {
            return true;
        }
        return false;
    }
}
