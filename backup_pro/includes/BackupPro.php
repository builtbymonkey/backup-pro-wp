<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./backup_pro/includes/BackupPro.php
 */
 
require_once 'vendor/autoload.php';

use mithra62\BackupPro\BackupPro AS BpInterface;

/**
 * Backup Pro - Wordpress Backup Pro Library 
 *
 * Contains the methods Wordpress needs to get started
 *
 * @package 	Wordpress
 * @author		Eric Lamb <eric@mithra62.com>
 */
class BackupPro implements BpInterface 
{
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 * @var BackupProLoader
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name = self::name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version = self::version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() 
	{
		//$this->version = 

		$this->loadDependencies();
		$this->setLocale();
		$this->defineAdminHooks();
		$this->definePublicHooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function loadDependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/BackupProLoader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/BackupProi18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/BackupProAdmin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/BackupProPublic.php';
		
		/**
		 * Now load up all the controllers
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/controllers/BackupProDashboardController.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/controllers/BackupProSettingsController.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/controllers/BackupProStorageController.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/controllers/BackupProBackupController.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/controllers/BackupProManageController.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/controllers/BackupProRestoreController.php';

		$this->loader = new BackupProLoader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function setLocale() {

		$plugin_i18n = new BackupProi18n();
		$plugin_i18n->setDomain( $this->getPluginName() );

		$this->loader->addAction( 'plugins_loaded', $plugin_i18n, 'loadPluginTextdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function defineAdminHooks() {

		$plugin_admin = new BackupProAdmin();
		$plugin_admin->setContext($this);

		$this->loader->addAction( 'admin_enqueue_scripts', $plugin_admin, 'enqueueStyles' );
		$this->loader->addAction( 'admin_enqueue_scripts', $plugin_admin, 'enqueueScripts' );
		$this->loader->addAction( 'admin_menu', $plugin_admin, 'loadMenu' );
		$this->loader->addAction( 'admin_notices' , $plugin_admin, 'errorNotices');
		
		$this->loader->addAction( 'admin_init' , $plugin_admin, 'procSettings');
		$this->loader->addAction( 'admin_init' , $plugin_admin, 'procStorageAdd');
		$this->loader->addAction( 'admin_init' , $plugin_admin, 'procStorageEdit');
		$this->loader->addAction( 'admin_init' , $plugin_admin, 'procStorageRemove');
		$this->loader->addAction( 'admin_init' , $plugin_admin, 'procConfirmBackup');
		$this->loader->addAction( 'admin_init' , $plugin_admin, 'procBackupRemove');
		$this->loader->addAction( 'admin_init' , $plugin_admin, 'procBackupNote');
		$this->loader->addAction( 'admin_init' , $plugin_admin, 'procBackupDatabase');
		$this->loader->addAction( 'admin_init' , $plugin_admin, 'procBackupFiles');
		$this->loader->addAction( 'admin_init' , $plugin_admin, 'downloadBackup');
		$this->loader->addAction( 'admin_init' , $plugin_admin, 'procDbRestore');
		
		
		
		$this->loader->addFilter( 'plugin_action_links_'.$this->plugin_name.'/'.$this->plugin_name.'.php', $plugin_admin, 'pluginLinks');
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function definePublicHooks() {

		$plugin_public = new BackupProPublic( $this->getPluginName(), $this->getVersion() );

		$this->loader->addAction( 'wp_enqueue_scripts', $plugin_public, 'enqueueStyles' );
		$this->loader->addAction( 'wp_enqueue_scripts', $plugin_public, 'enqueueScripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function getPluginName() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function getLoader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function getVersion() {
		return $this->version;
	}
	
	/**
	 * Creates the Settings menu for the view script
	 * @return multitype:multitype:string  multitype:string unknown
	 */
	public function getSettingsViewMenu()
	{
	    $menu = array(
	        'general' => array('url' => 'general', 'target' => '', 'div_class' => ''),
	        'db' => array('url' => 'db', 'target' => '', 'div_class' => ''),
	        'files' => array('url' => 'files', 'target' => '_self', 'div_class' => ''),
	        'cron' => array('url' => 'cron', 'target' => '', 'div_class' => ''),
	        'storage' => array('url' => 'storage', 'target' => '', 'div_class' => ''),
	        'integrity_agent' => array('url' => 'integrity_agent', 'target' => '', 'div_class' => ''),
	        'license' => array('url' => 'license', 'target' => '', 'div_class' => ''),
	    );
	
	    return $menu;
	}
	
	/**
	 * Creates the Dashboard menu for the view script
	 * @return multitype:multitype:string  multitype:string unknown
	 */
	public function getDashboardViewMenu()
	{
	    $menu = array(
	        'home' => array('url' => '', 'target' => '', 'div_class' => ''),
	        'db' => array('url' => 'db_backups', 'target' => '', 'div_class' => ''),
	        'files' => array('url' => 'file_backups', 'target' => '_self', 'div_class' => '')
	    );
	    return $menu;
	}
	
	/**
	 * Returns a string to use for the form field errors
	 * @return string
	 */
	public function displayFormErrors($errors)
	{
	    if( is_string($errors) && $errors != '')
	    {
	        //$errors = array($errors);
	    }
	
	    $return = '';
	    if( is_array($errors) && count($errors) >= 1)
	    {
	        $return = '<ul style="padding-top:5px;">';
	        foreach($errors AS $error)
	        {
	            $return .= '<li class="notice">'.$error.'</li>';
	        }
	        $return .= '</ul>';
	    }
	
	    return $return;
	}
}
