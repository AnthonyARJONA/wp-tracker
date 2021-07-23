<?php

//block direct access to ext
if(!defined( 'ABSPATH')) {
    exit;
}

class WP_Tracker_Database
{

    private $database;
    private $tables = [];
    private $db_prefix;
    private $query;

    public function __construct() {
        global $wpdb;
        $this->database = $wpdb;
        $this->db_prefix = $wpdb->prefix;
        $this->tables    =  [
            'wordpress_tracker_settings' => [
                'name' => [
                    'type' => 'varchar',
                    'size' => 255,
                ],
                'description' => [
                    'type' => 'varchar',
                    'size' => 255,
                ],
                'value' => [
                    'type' => 'longtext',
                ]
            ],
            'wordpress_tracker_visitor' => [
                'slug' => [
                    'type' => 'varchar',
                    'size' => 255,
                ],
                'ip' => [
                    'type' => 'varchar',
                    'size' => 255,
                    'null_permitted' => true
                ],
                'city' => [
                    'type' => 'varchar',
                    'size' => 255,
                    'null_permitted' => true
                ],
                'country' => [
                    'type' => 'varchar',
                    'size' => 255,
                    'null_permitted' => true
                ],
                'loc' => [
                    'type' => 'varchar',
                    'size' => 255,
                    'null_permitted' => true
                ],
                'postal' => [
                    'type' => 'varchar',
                    'size' => 255,
                    'null_permitted' => true
                ],
                'timezone' => [
                    'type' => 'varchar',
                    'size' => 255,
                    'null_permitted' => true
                ]
            ],
        ];

    }

    public function exec() {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($this->query);
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
        return $this->getDatabase()->get_charset_collate();
    }

    public function createQueryBuilder() {
        foreach ($this->tables as $table_name => $table_columns) {
            $column_exclude = ['isActive', 'isDeleted', 'createAt', 'updateAt'];
            $index_authorized = ['PRIMARY KEY', 'UNIQUE', 'INDEX', 'FULLTEXT', 'SPATIAL'];

            $this->query .= 'CREATE TABLE IF NOT EXISTS `' . $this->db_prefix . $table_name . '` (';
            $this->query .= '`id` int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY(id),';

            foreach ($table_columns as $column_name => $column_params) {
                if (!in_array($column_name, $column_exclude)) {
                    $this->query .= '`' . $column_name . '` ';
                    $this->query .= $column_params['type'];
                    $this->query .= isset($column_params['size']) ? '(' . $column_params['size'] . ')' : '';
                    $this->query .= isset($column_params['null_permitted']) ? '' : ' NOT NULL';
                    $this->query .= isset($column_params['default_value']) ? ' DEFAULT ' . (is_string($column_params['default_value']) ? ($column_params['default_value'] != 'null' ? '\'' : '') . strtoupper($column_params['default_value']) . ($column_params['default_value'] != 'null' ? '\'' : '') : $column_params['default_value']) : '';
                    $this->query .= (isset($column_params['auto_increment']) && $column_params['auto_increment'] != false) ? ' AUTO_INCREMENT ' : '';
                    $this->query .= (isset($column_params['index']) && !in_array($column_params['index'], $index_authorized)) ? $column_params['index'] : '';
                    $this->query .= isset($column_params['comment']) ? ' COMMENT \'' . $column_params['comment'] . '\'' : '';
                    $this->query .= ', ';
                }
            }
            $this->query .= ' `createAt` DATETIME DEFAULT CURRENT_TIMESTAMP,';
            $this->query .= ' `updateAt` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP';
            $this->query .= ') ' . $this->getCharsetCollate() . ';';
        }
        return $this->query;
    }

    public function deleteQueryBuilder() {
        foreach ($this->tables as $table_name => $table_columns) {
            $this->query .= 'DROP TABLE IF EXISTS ' . $this->db_prefix . $table_name . ';';
        }
        return $this->query;
    }

    public function insertVisitor($ip_info = null) {
        if(is_null($ip_info)) {
            $ip_info = WP_Tracker_Api::getIpInfo();
        }
        $this->query .= 'INSERT INTO ' . $this->db_prefix . 'wordpress_tracker_visitor' . ' (`slug`, `ip`, `city`, `country`, `loc`, `postal`, `timezone`) VALUE ' . "('" .
        WP_Tracker_Track::getSlug() . "', '" .
        (isset($ip_info['ip']) ? $ip_info['ip'] : null) . "', '" .
        (isset($ip_info['city']) ? $ip_info['city'] : null) . "', '" .
        (isset($ip_info['country']) ? $ip_info['country'] : null) . "', '" .
        (isset($ip_info['loc']) ? $ip_info['loc'] : null) . "', '" .
        (isset($ip_info['postal']) ? $ip_info['postal'] : null) ."', '" .
        (isset($ip_info['timezone']) ? $ip_info['timezone'] : null) . "'); ";
        return $this->query;
    }

}