<?php

//block direct access to ext
if(!defined( 'ABSPATH')) {
    exit;
}

class WP_Tracker_Api
{

    const IPINFO_URI = 'https://ipinfo.io/';
    const IPINFO_KEY = '0165528f5868fd';

    /**
     * return api data in array
     * @return array|string
     */
    public static function getIpInfo() {
        try {
            //IPInfo free 50000 rate limit per month
                $curl = new WP_Tracker_Curl(self::IPINFO_URI . WP_Tracker_Client::getClientIp(), [
                CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . self::IPINFO_KEY]
            ]);
            return json_decode($curl->exec(), true);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

}
