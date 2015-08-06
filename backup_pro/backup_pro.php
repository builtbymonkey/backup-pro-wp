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


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_plugin_name() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-activator.php';
    Plugin_Name_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_plugin_name() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-deactivator.php';
    Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
*/
require plugin_dir_path( __FILE__ ) . 'includes/class_backup_pro.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_name() {

    $plugin = new Plugin_Name();
    $plugin->run();

}
run_plugin_name();