<?php

//block direct access to ext
if(!defined( 'ABSPATH')) {
    exit;
}

class WP_Tracker_Client
{

    /**
     * Return user ip
     * @param string $client_ip
     * @return string
     */
    public static function getClientIp($client_ip = null)
    {
        if(is_null($client_ip)) {
            $keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
            foreach($keys as $key)
            {
                if (!empty($_SERVER[$key]) && filter_var($_SERVER[$key], FILTER_VALIDATE_IP))
                {
                    $client_ip = $_SERVER[$key];
                }
            }
            $client_ip = 'UNKNOWN';
        }
        return $client_ip;
    }

}