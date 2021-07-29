<?php

//block direct access to ext
if(!defined( 'ABSPATH')) {
    exit;
}

class WP_Tracker_Helper
{

    public static function asset($filename) {
        return plugins_url('WP-Tracker/src/resources/' . $filename);
    }

    public static function getMenuIcon() {
        return 'data:image/svg+xml;base64,' . base64_encode('<svg fill="#f0f0f1" width="2048" height="1792" viewBox="0 0 2048 1792" xmlns="http://www.w3.org/2000/svg"><path d="M640 896v512h-256v-512h256zm384-512v1024h-256v-1024h256zm1024 1152v128h-2048v-1536h128v1408h1920zm-640-896v768h-256v-768h256zm384-384v1152h-256v-1152h256z"/></svg>');
    }

    public static function debug(...$params) {
        echo '<style>';
        echo 'pre {
                white-space: pre-wrap;       /* Since CSS 2.1 */
                white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
                white-space: -pre-wrap;      /* Opera 4-6 */
                white-space: -o-pre-wrap;    /* Opera 7 */
                word-wrap: break-word;       /* Internet Explorer 5.5+ */
                font-size: 15px;
            }';
        echo '</style>';
        echo '<pre style="background-color: #1e2125; color: #ffffff; padding: 20px">';
        foreach($params as $param) {
            print_r($param);
        }
        echo '</pre>';
    }

}