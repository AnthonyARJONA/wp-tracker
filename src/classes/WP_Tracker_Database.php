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
        $this->database  = $wpdb;
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
                    'type' => 'text',
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
                ],
                'city' => [
                    'type' => 'varchar',
                    'size' => 255,
                    'null' => true,
                    'default_value' => 'null',
                ],
                'country' => [
                    'type' => 'varchar',
                    'size' => 255,
                    'null' => true,
                    'default_value' => 'null',
                ],
                'loc' => [
                    'type' => 'varchar',
                    'size' => 255,
                    'null' => true,
                    'default_value' => 'null',
                ],
                'postal' => [
                    'type' => 'varchar',
                    'size' => 255,
                    'null' => true,
                    'default_value' => 'null',
                ],
                'timezone' => [
                    'type' => 'varchar',
                    'size' => 255,
                    'null' => true,
                    'default_value' => 'null',
                ]
            ],
        ];
    }

    public function exec($query = null) {
        if(!is_null($query)) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($query);
        }
        return;
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
                    $this->query .= isset($column_params['null']) ? '' : ' NOT NULL';
                    $this->query .= isset($column_params['default_value']) ? ' DEFAULT ' . (is_string($column_params['default_value']) ? ($column_params['default_value'] != 'null' ? '\'' : '') . strtoupper($column_params['default_value']) . ($column_params['default_value'] != 'null' ? '\'' : '') : $column_params['default_value']) : '';
                    $this->query .= (isset($column_params['auto_increment']) && $column_params['auto_increment'] != false) ? ' AUTO_INCREMENT ' : '';
                    $this->query .= (isset($column_params['index']) && !in_array($column_params['index'], $index_authorized)) ? $column_params['index'] : '';
                    $this->query .= isset($column_params['comment']) ? ' COMMENT \'' . $column_params['comment'] . '\'' : '';
                    $this->query .= ', ';
                }
            }
            $this->query .= ' `createAt` DATETIME DEFAULT CURRENT_TIMESTAMP,';
            $this->query .= ' `updateAt` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP';
            $this->query .= ') ' . $this->getCharsetCollate() . '; ';
            $this->exec($this->query);
        }
        return;
    }

    public function deleteQueryBuilder() {
        foreach ($this->tables as $table_name => $table_columns) {
            $this->query .= "DROP TABLE IF EXISTS '" . $this->db_prefix . $table_name . "';";
            $this->exec($this->query);
        }
        return;
    }

    public function insertVisitor($ip_info = null) {
        if(is_null($ip_info)) {
            $ip_info = WP_Tracker_Api::getIpInfo();
        }
        $columns = ['ip', 'city', 'country', 'loc', 'postal', 'timezone'];
        $columns_to_insert = '';
        $data_to_insert = '';
        foreach ($columns as $column) {
            if(isset($ip_info[$column]) && !empty($ip_info[$column])) {
                $columns_to_insert .= '`' . $column . '`, ';
                $data_to_insert .= "'" . $ip_info[$column] . "', ";
            }
        }
        $columns_to_insert .= '`slug`';
        $data_to_insert .= "'" . WP_Tracker_Track::getSlug() . "'";

        $this->query .= 'INSERT INTO ' . $this->db_prefix . 'wordpress_tracker_visitor' . ' ('. $columns_to_insert .') VALUE ' . '(' . $data_to_insert . ')';
        $this->exec($this->query);

        return $this->query;
    }

    public function getTodayVisit() {
        $query = "SELECT COUNT(DISTINCT `ip`) AS `today_visit` FROM " . $this->db_prefix . 'wordpress_tracker_visitor' . " WHERE DATE_FORMAT(createAt, '%Y-%m-%d') = CURDATE()";
        $result = $this->getDatabase()->get_row($query);
        if($result) {
            return $result->today_visit;
        }
        return null;
    }

    public function getVisitLastSevenDays() {
        $query = "SELECT COUNT(DISTINCT `ip`, DATE_FORMAT(createAt, '%Y-%m-%d')) AS `visit_between_seven_days` FROM " . $this->db_prefix . 'wordpress_tracker_visitor' . " WHERE createAt BETWEEN DATE_ADD(NOW(), INTERVAL -7 DAY) AND NOW()";
        $result = $this->getDatabase()->get_row($query);
        if($result) {
            return $result->visit_between_seven_days;
        }
        return null;
    }

    public function getUniqueVisitThisMonth() {
        $query = "SELECT COUNT(DISTINCT `ip`, DATE_FORMAT(createAt, '%Y-%m-%d')) AS `uniq_visit_this_month` FROM " . $this->db_prefix . 'wordpress_tracker_visitor' . " WHERE DATE_FORMAT(createAt, '%m') = DATE_FORMAT(NOW(), '%m')";
        $result = $this->getDatabase()->get_row($query);
        if($result) {
            return $result->uniq_visit_this_month;
        }
        return null;
    }

}


