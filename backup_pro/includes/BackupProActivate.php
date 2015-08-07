<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./backup_pro/includes/BackupProActivate.php
 */

/**
 * Backup Pro - Wordpress Activate Object
 *
 * Handles installing Backup Pro for Wordpress
 *
 * @package 	Wordpress
 * @author		Eric Lamb <eric@mithra62.com>
 */
class BackupProActivate 
{
    /**
     * The version our settings table is at
     * @var float
     */
    private static $table_version = '1.0';

    /**
     * Wrapper to install everything
     */
	public static function activate() 
	{
	    global $wpdb;
	    $charset_collate = $wpdb->get_charset_collate();
	    
        $sql = "
            CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."backup_pro_settings (
              id int(10) unsigned NOT NULL AUTO_INCREMENT,
              setting_key varchar(60) NOT NULL DEFAULT '',
              setting_value text NOT NULL,
              serialized int(1) DEFAULT '0',
              PRIMARY KEY  (id)
            ) $charset_collate;
        ";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        
        add_option( 'bp3_db_version', self::$table_version );        
	}

}
