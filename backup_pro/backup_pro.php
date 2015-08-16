<?php
/**
* Plugin Name: Backup Pro
* Plugin URI: https://mithra62.com/projects/view/backup-pro
* Description: Backup Pro adds simple, 1 click, file and database backup and database restoration. Backup Pro allows for abstract and redundant storage of your backups through any combination of Local Storage, Amazon S3, Rackspace Cloud Files, Google Cloud Storage, FTP, and even an Email Inbox (if that's your thing). 
* Version: 3.1
* Author: mithra62
* Author URI: http://mithra62.com/
* License: Commercial
* License URI: https://mithra62.com/license-agreement
**/

/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./backup_pro.php
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

function activate_backup_pro() 
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/BackupProActivate.php';
    BackupProActivate::activate();
}

function deactivate_backup_pro() 
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/BackupProDeactivate.php';
    BackupProDeactivate::deactivate();
}


if( !class_exists('BackupPro') )
{
    require plugin_dir_path( __FILE__ ) . 'includes/BackupPro.php';
}

if( !function_exists('run_backup_pro') )
{
    function run_backup_pro() 
    {
        if( in_array( 'backup_pro/backup_pro.php', get_option( 'active_plugins', array() )))
        {
            $plugin = new BackupPro();
            $plugin->run();
        }
    
    }
}

register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), 'activate_backup_pro' );
register_deactivation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), 'deactivate_backup_pro' );

run_backup_pro();