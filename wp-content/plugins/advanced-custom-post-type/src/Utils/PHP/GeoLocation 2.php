<?php

namespace ACPT\Utils\PHP;

use ACPT\Core\Models\Settings\SettingsModel;
use ACPT\Core\Repository\SettingsRepository;

class GeoLocation
{
    /**
     * @param string $address
     *
     * @return null|array
     * @throws \Exception
     */
    public static function getCoordinates($address)
    {
        $googleMapKey = SettingsRepository::getSingle(SettingsModel::GOOGLE_MAPS_API_KEY_KEY);
        $apiKey = ($googleMapKey !== null) ? $googleMapKey->getValue() : null;

        // try with Google maps
        if($apiKey){
            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&key='.$apiKey;
            $request = CURL::get($url);
            $json = json_decode($request);

            if(!isset($json->error_message)){
                $data['lat'] = $json->results[0]->geometry->location->lat;
                $data['lng'] = $json->results[0]->geometry->location->lng;
                return $data;
            }
        }

        $url = 'https://nominatim.openstreetmap.org/search?q='.urlencode($address).'&format=json';
        $request = CURL::get($url);
        $json = json_decode($request);

        if(empty($json)){
            return null;
        }

        $data['lat'] = $json[0]->lat;
        $data['lng'] = $json[0]->lon;

        return $data;
    }
}