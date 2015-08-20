<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./backup_pro/includes/BackupProDeactivate.php
 */

/**
 * Backup Pro - Wordpress Dectivate Object
 *
 * Handles deactivating Backup Pro for Wordpress
 *
 * @package 	Wordpress
 * @author		Eric Lamb <eric@mithra62.com>
 */
class BackupProDeactivate {

    /**
     * do it
     */
	public static function deactivate() 
	{
	    global $wpdb;
	    
	    delete_option( 'bp3_db_version' );
	    
        $sql = "DROP TABLE IF EXISTS ".$wpdb->prefix."backup_pro_settings";
        $wpdb->query($sql);
        $wpdb->flush();
	}

}
