<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./backup_pro/admin/BackupProAdmin.php
 */
 
use mithra62\BackupPro\Platforms\Controllers\Wordpress AS WpController;
use mithra62\BackupPro\BackupPro AS BpInterface;

/**
 * Backup Pro - Admin Library
 *
 * Abstracts setting up the administration details
 *
 * @package 	Wordpress
 * @author		Eric Lamb <eric@mithra62.com>
 */
class BackupProAdmin extends WpController implements BpInterface
{
    /**
     * The shortname for the plugin
     * @var string
     */
	private $plugin_name = self::name;

	/**
	 * The version of the plugin
	 * @var number
	 */
	private $version = self::version;
	
	/**
	 * An instance of the BackupPro object
	 * @var BackupPro
	 */
	private $context = null;

	/**
	 * sets up the inital admin hooks
	 * @param unknown $plugin_name
	 * @param unknown $version
	 */
	public function __construct() 
	{
	    parent::__construct();
		add_action('admin_init', array($this, 'proc_settings'));
		add_action('admin_init', array($this, 'proc_storage_add'));
		add_action('admin_init', array($this, 'proc_storage_edit'));
		add_action('admin_init', array($this, 'proc_storage_remove'));
		add_action('admin_init', array($this, 'proc_backup_note'));
		add_action('admin_init', array($this, 'backup_database'));
	}
	
	public function setContext(BackupPro $context)
	{
	    $this->context = $context;
	    return $this;
	}
	
	public function proc_settings()
	{
        if( $_SERVER['REQUEST_METHOD'] == 'POST' && $this->getPost('page') == 'backup_pro/settings' )
        {
            $data = array();
            $data = array_map( 'stripslashes_deep', $_POST );
    
            $variables['form_data'] = array_merge(array('db_backup_ignore_tables' => '', 'db_backup_ignore_table_data' => ''), $data);
            $backup = $this->services['backups'];
            $backups = $backup->setBackupPath($this->settings['working_directory'])->getAllBackups($this->settings['storage_details']);
            $data['meta'] = $backup->getBackupMeta($backups);
            $extra = array('db_creds' => $this->platform->getDbCredentials());
            $settings_errors = $this->services['settings']->validate($data, $extra);
            if( !$settings_errors )
            {
                if( $this->services['settings']->update($data) )
                {
                    //ee()->session->set_flashdata('message_success', $this->services['lang']->__('settings_updated'));
                    wp_redirect($this->url_base.'settings&section='.$this->getPost('section').'&updated=yes');
                    exit;
                }
            }
        }
        else
        {
            
            if( $this->getPost('updated') == 'yes' && $this->getPost('page') == 'backup_pro/settings' )
            {
                //$this->context->loader->addAction( 'admin_notices' , $this, 'settingsNotices');
                add_action( 'admin_notices', array( $this, 'settingsNotices' ), 30, array('settings_updated'));
            }
        }
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
	    $page = $page->setBackupLib($this->context);
	    
	    $section = $this->getPost('section');
	    switch( $section )
	    {
	        case 'db_backups':
	            $page->db_backups();
            break;
            
	        case 'file_backups':
	            $page->file_backups();
            break;
            
	        case 'dashboard':
	        default:
	            $page->index();
            break;
	    }
	}
	
	public function settings()
	{
	    $section = $this->getPost('section');
	    if($section == 'storage')   
	    {
            $action = $this->getPost('action');
	        $page = new BackupProStorageController($this);
	        $page = $page->setBackupLib($this->context);
	        switch($action)
	        {
	            case 'new':
	                $page->new_storage();
                break;
	            default:
	                $page->view_storage();
                break;
	        }
	    }
	    else 
	    {
	        $page = new BackupProSettingsController($this);
	        $page->setBackupLib($this->context)->settings();	        
	    }
	}
	
	public function backup_files()
	{
	    
	}
	
	public function backup_db()
	{
	    $page = new BackupProBackupController($this);
	    $page->backup();
	}
	
	public function backup_database()
	{
	    if( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['go_db']) && $_POST['go_db'] == 'ok' && check_admin_referer( 'backup_db' ) )
	    {
	        $page = new BackupProBackupController();
	        $page->backup_database();
	    }
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
        add_submenu_page( 'backup_pro', 'Settings', 'Settings', 'manage_options', 'backup_pro/settings', array($this, 'settings'));
        
        //these shouldn't show up in the navigation
        add_submenu_page( null, 'Database Backups', null, 'manage_options', 'backup_pro/backup_database', array($this, 'backup_database'));
        //add_submenu_page( 'backup_pro', 'New Storage', null, 'manage_options', 'backup_pro/new_storage', array($this, 'settings'));
        //add_submenu_page( 'backup_pro', 'Newd Storage', null, 'manage_options', 'backup_pro/new_storagge', array($this, 'settings'));
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
            	    echo"<div class=\"$class\"> <p>".esc_html__($this->view_helper->m62Lang($error));
            	    if( $error == 'no_storage_locations_setup' )
            	    {
            	        echo ' <a href="'.$this->url_base.'settings&section=storage&action=new&engine=local">Setup Storage Location</a>';
            	    }
            	    elseif( $error == 'license_number' || $error == 'missing_license_number' )
            	    {
            	        echo ' <a href="'.$this->url_base.'settings&section=license">Enter License</a> or <a href="https://mithra62.com/projects/view/backup-pro">Purchase a License</a>';
            	    }
            	    echo "</p></div>";
	            }
	        }
	    }
	}
	
	public function settingsNotices()
	{
	    $class =  $class = " updated ";
	    echo"<div class=\"$class\"> <p>".esc_html__($this->view_helper->m62Lang('settings_updated'));

	    echo "</p></div>";
	}

}
