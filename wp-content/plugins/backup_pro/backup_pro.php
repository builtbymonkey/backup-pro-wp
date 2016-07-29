<?php
/**
 * Plugin Name: Backup Pro
 * Plugin URI: http://backup-pro.com/
 * Description: Backup Pro adds simple, 1 click, file and database backup and database restoration. 
 * Version: 3.4.0.1
 * Author: mithra62
 * Author URI: https://mithra62.com/
 * License: Commercial
 * License URI: https://mithra62.com/license-agreement
**/

/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./backup_pro.php
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( defined('WP_CLI') && WP_CLI ) {
    return; //we don't work nicely with WP_CLI since Backup Pro uses the wp-cli-tools library too
}

if( !function_exists('activateBackupPro') )
{
    /**
     * Silly little function to activate the plugin
     */
    function activateBackupPro() 
    {
        if (version_compare(phpversion(), '5.4.0', '<')) {
            throw new \Exception('You must be run PHP 5.4 or greater to use this package.');
        }
         
        if (!function_exists('mysqli_report') && !class_exists('PDO')) {
            throw new \Exception('Backup Pro requires either the mysqli or PDO extension for database use.');
        }
         
        if (!function_exists('mb_check_encoding')) {
            throw new \Exception('Backup Pro requires the Multi Byte extension to ensure proper string encoding.');
        }
         
        if (!function_exists('curl_init')) {
            throw new \Exception('Backup Pro requires the Curl extension to transfer backups to remote locations.');
        }
        
        require_once plugin_dir_path( __FILE__ ) . 'includes/BackupProActivate.php';
        BackupProActivate::activate();
    }
}

if( !function_exists('deactivateBackupPro') )
{
    /**
     * Another silly little function for deactivating the plugin
     */
    function deactivateBackupPro() 
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/BackupProDeactivate.php';
        BackupProDeactivate::deactivate();
    }
}

if( !class_exists('BackupPro') )
{
    /**
     * Grab the BackupPro object 
     */
    require plugin_dir_path( __FILE__ ) . 'includes/BackupPro.php';
}

if( !function_exists('run_backup_pro') )
{
    /**
     * Silly little funciton to execute the Backup Pro MVC silliness
     */
    function run_backup_pro() 
    {
        if( in_array( 'backup_pro/backup_pro.php', get_option( 'active_plugins', array() )))
        {
            $plugin = new BackupPro();
            $plugin->run();
        }
    
    }
}

if( !function_exists('procBackupProNoteAction') )
{
    /**
     * And... ANOTHER silly little function for wrapping up the backup note ajax
     */
    function procBackupProNoteAction()
    {
        $page = new BackupProManageController();
        $page = $page->setBackupLib( new BackupPro() );
        $page->updateBackupNote();
        wp_die();
    }

    add_action( 'wp_ajax_procBackupProNoteAction', 'procBackupProNoteAction' );
}

//fist pump
register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), 'activateBackupPro' );
register_deactivation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), 'deactivateBackupPro' );
run_backup_pro();