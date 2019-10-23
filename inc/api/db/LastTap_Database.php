<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/controller
 * @see LastTap_BaseController
 */

defined('ABSPATH') || exit;

class LastTap_Database
{


    public static $table_name;
    public $version;
    private $primary_key;

    public function __construct()
    {

        global $wpdb;

        self::$table_name;
        $this->primary_key = 'id';
        $this->version = '1.0';


        $this->init();


    }

    public function init()
    {

        register_uninstall_hook(__FILE__, 'drop_database');
        register_activation_hook(__FILE__, 'create_table');

    }

    public function create_table($table = null)
    {

        global $wpdb;

        self::$table_name = $table;

        $charset_collate = $wpdb->get_charset_collate();

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');



        $sql = "CREATE TABLE " . $wpdb->prefix . self::$table_name . " (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        data longtext NOT NULL,
        PRIMARY KEY  (id)
        ) CHARACTER SET utf8 COLLATE $charset_collate;";

        dbDelta($sql);


        update_option(self::$table_name . '_db_version', $this->version);
    }

    public function insert_data(string $table, array $data, array $dataType)
    {
        global $wpdb;

        return $wpdb->insert($table, $data, $dataType);


    }

    public function select_data($table)
    {
        global $wpdb;


        $obj = $wpdb->get_results("SELECT *  FROM  $table ");

        return $obj;
    }

    public function update_data()
    {

    }

    public function delete_data()
    {
        global $wpdb;


    }

    public function on_delete_blog()
    {
        global $wpdb;
        $tabelas [] = $wpdb->query("DROP table IF EXISTS " . $wpdb->prefix . self::$table_name);
        return $tabelas;
    }

}

