<?php
use mithra62\BackupPro\Platforms\Controllers\Wordpress AS WpController;

class BackupProAdmin extends WpController
{
	private $plugin_name;

	private $version;
	
	/**
	 * An instance of the BackupPro object
	 * @var BackupPro
	 */
	private $context = null;

	public function __construct( $plugin_name, $version ) 
	{
	    parent::__construct();
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action('admin_init', array($this, 'proc_settings'));
		add_action('admin_init', array($this, 'proc_storage_add'));
		add_action('admin_init', array($this, 'proc_storage_edit'));
		add_action('admin_init', array($this, 'proc_storage_remove'));
		add_action('admin_init', array($this, 'proc_backup_note'));
		add_action('admin_init', array($this, 'proc_remove_backup'));
	}
	
	public function setContext(BackupPro $context)
	{
	    $this->context = $context;
	    return $this;
	}
	
	public function proc_settings()
	{
	    //wp_redirect('/');
	}
	
	public function proc_storage_add()
	{
	    //wp_redirect('/');
	}
	
	public function proc_storage_edit()
	{
	    //wp_redirect('/');
	}
	
	public function proc_storage_remove()
	{
	    //wp_redirect('/');
	}
	
	public function proc_backup_note()
	{
	    //wp_redirect('/');
	}
	
	public function proc_remove_backup()
	{
	    //wp_redirect('/');
	}
	
	public function dashboard()
	{
	    $page = new BackupProDashboardController($this);
	    $page->setBackupLib($this->context)->index();
	}
	
	public function settings()
	{
	    
	}
	
	public function backup_files()
	{
	    
	}
	
	public function backup_db()
	{
	    
	}

	public function enqueueStyles() 
	{
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/backup_pro_admin.css', array(), $this->version, 'all' );
	}

	public function enqueueScripts() 
	{
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );
	}
	
	public function loadMenu()
	{
	    add_menu_page('Backup Pro', 'Backup Pro', 'manage_options', 'backup_pro', array($this, 'dashboard'), plugin_dir_url( __FILE__ ).'images/bp3_32.png', '23.56');
        add_submenu_page( 'backup_pro', 'Dashboard', 'Dashboard', 'manage_options', 'backup_pro', array($this, 'dashboard'));
        add_submenu_page( 'backup_pro', 'Backup Database', 'Backup Database', 'manage_options', 'backup_pro/backup_db', array($this, 'backup_db'));
        add_submenu_page( 'backup_pro', 'Backup Files', 'Backup Files', 'manage_options', 'backup_pro/backup_files', array($this, 'backup_files'));
        
        //these shouldn't show up in the navigation
        add_submenu_page( null, 'New Storage', 'New Storage', 'manage_options', 'backup_pro/settings&action=new_storage', array($this, 'settings'));
        add_submenu_page( 'backup_pro', 'Settings', 'Settings', 'manage_options', 'backup_pro/settings', array($this, 'settings'));
	}
	
	public function pluginLinks($links)
	{
	    $links['settings'] = sprintf( '<a href="%s"> %s </a>', admin_url( 'admin.php?page=backup_pro/settings' ), __( 'Settings', 'plugin_domain' ) );
	    return $links;
	}
	
	public function errorNotices()
	{
	    $screen_id = get_current_screen()->id;
	    $display_notice = get_user_meta( get_current_user_id(), '_wptuts_display_notice', true );

	    if ( !$display_notice && strpos($screen_id, 'backup_pro') !== FALSE ) 
	    {
	        $errors = $this->services['errors']->checkStorageLocations($this->settings['storage_details'])
                        	         ->licenseCheck($this->settings['license_number'], $this->services['license'])
                        	         ->getErrors();
	        
	        if( $errors && count($errors) >= 1)
	        {	            
	            foreach($errors AS $error)
	            {
        	        $class = "error";
            	    $message = $error;
            	    echo"<div class=\"$class\"> <p>$message</p></div>";
	            }
	        }
	    }
	}

}
