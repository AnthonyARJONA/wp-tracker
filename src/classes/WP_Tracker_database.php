<?php

//block direct access to ext
if(!defined( 'ABSPATH')) {
    exit;
}

class WP_Tracker_database
{

    private $database;
    private $tables = [];

    public function __construct() {
        global $wpdb;
        $this->database = $wpdb;
        $this->tables    =  [
            'tracker' => $this->database->prefix . 'wordpress_tracker'
        ];
    }

    public function init() {
        $this->createTableIfNotExist();
    }

    public function getDatabase() {
        return $this->database;
    }

    public function getTableName($tableName) {
        if (!array_key_exists($tableName, $this->tables) ) {
            return false;
        }
        return $this->tables[$tableName];
    }

    protected function getCharsetCollate() {
        return $this->database->get_charset_collate();
    }

    protected function createTableIfNotExist() {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->tables['tracker']} (
                      id int(5) NOT NULL AUTO_INCREMENT,
                      visit int(5) DEFAULT NULL,
                      PRIMARY KEY (id)
            ) {$this->getCharsetCollate()};";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }


}