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
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $client_ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $client_ip = $_SERVER['REMOTE_ADDR'];
            }
        }
        return $client_ip;
    }

}