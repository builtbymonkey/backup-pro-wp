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
 * Backup Pro - Wordpress Admin Dispatcher
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
	 * Below starts the initialization scripts and setup for the WP event system
	 * Note that none of this feels right to me...

	/**
	 * Sets the CSS file for the Admin 
	 */
	public function enqueueStyles() 
	{
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/backup_pro_admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Loads up the JavaScript for the Admin
	 */
	public function enqueueScripts() 
	{
		wp_enqueue_script( 'bpchosen', plugin_dir_url( __FILE__ ) . 'js/chosen.jquery.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'bpdashboard', plugin_dir_url( __FILE__ ) . 'js/dashboard.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'bpsettings', plugin_dir_url( __FILE__ ) . 'js/settings.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'bpglobal', plugin_dir_url( __FILE__ ) . 'js/global.js', array( 'jquery' ), $this->version, true );
	}
	
	/**
	 * Defines the Left Menu for the Admin
	 */
	public function loadMenu()
	{
	    add_menu_page('Backup Pro', 'Backup Pro', 'manage_options', 'backup_pro', array($this, 'dashboard'), plugin_dir_url( __FILE__ ).'images/bp3_32.png', '23.56');
        add_submenu_page( 'backup_pro', 'Dashboard', 'Dashboard', 'manage_options', 'backup_pro', array($this, 'dashboard'));
        add_submenu_page( 'backup_pro', 'Backup Database', 'Backup Database', 'manage_options', 'backup_pro/confirm_backup_db', array($this, 'confirmBackupDb'));
        add_submenu_page( 'backup_pro', 'Backup Files', 'Backup Files', 'manage_options', 'backup_pro/confirm_backup_files', array($this, 'confirmBackupFiles'));
        add_submenu_page( 'backup_pro', 'Settings', 'Settings', 'manage_options', 'backup_pro/settings', array($this, 'settings'));
        
        //these shouldn't show up in the navigation
        add_submenu_page( null, 'Backup Database', null, 'manage_options', 'backup_pro/backup_database', array($this, 'backupDatabase'));
        add_submenu_page( null, 'Backup Files', null, 'manage_options', 'backup_pro/backup_files', array($this, 'backupFiles'));
        add_submenu_page( null, 'New Storage', null, 'manage_options', 'backup_pro/download', array($this, 'downloadBackup'));
        //add_submenu_page( 'backup_pro', 'Newd Storage', null, 'manage_options', 'backup_pro/new_storagge', array($this, 'settings'));
	}
	
	/**
	 * Action to process the Settings
	 */
	public function procSettings()
	{
        if( $_SERVER['REQUEST_METHOD'] == 'POST' && $this->getPost('page') == 'backup_pro/settings' && $this->getPost('section') != 'storage' && check_admin_referer( 'bpsettings' ) )
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
                    wp_redirect($this->url_base.'settings&section='.$this->getPost('section').'&updated=yes');
                    exit;
                }
            }
        }
        else
        {
            if( $this->getPost('updated') == 'yes' && $this->getPost('page') == 'backup_pro/settings' )
            {
                add_action( 'admin_notices', array( $this, 'settingsNotices' ), 30, array('settings_updated'));
            }
        }
	}
	
	/**
	 * action to process adding a new storage engine
	 */
	public function procStorageAdd()
	{
	    if( $_SERVER['REQUEST_METHOD'] == 'POST' && $this->getPost('page') == 'backup_pro/settings' && $this->getPost('section') == 'storage' && check_admin_referer( 'bpsettings' ) )
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
	                wp_redirect($this->url_base.'settings&section='.$this->getPost('section').'&updated=yes');
	                exit;
	            }
	        }
	    }
	    else
	    {
	    
	        if( $this->getPost('updated') == 'yes' && $this->getPost('page') == 'backup_pro/settings' )
	        {
	            add_action( 'admin_notices', array( $this, 'settingsNotices' ), 30, array('settings_updated'));
	        }
	    }
	}
	
	public function procStorageEdit()
	{
	    //wp_redirect('/');
	}
	
	public function procStorageRemove()
	{
	    //wp_redirect('/');
	}
	
	public function procBackupNote()
	{
	    //wp_redirect('/');
	}
	
	public function procRemoveBackup()
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
	
	public function downloadBackup()
	{
	    if( $this->getPost('page') == 'backup_pro/download' && check_admin_referer( urlencode($this->getPost('id')) ) )
	    {
    	    $page = new BackupProManageController($this);
    	    $page->download();
    	    exit;
	    }
	}
	
	public function confirmBackupFiles()
	{
	    $page = new BackupProBackupController($this);
	    $page->backup('files');
	}
	
	public function confirmBackupDb()
	{
	    $page = new BackupProBackupController($this);
	    $page->backup();
	}
	
	public function backupDatabase()
	{
	    if( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['go_db']) && $_POST['go_db'] == 'ok' && check_admin_referer( 'backup_db' ) )
	    {
	        $page = new BackupProBackupController();
	        $page->backup_database();
	    }
	}
	
	public function backupFiles()
	{
	    if( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['go_files']) && $_POST['go_files'] == 'ok' && check_admin_referer( 'backup_files' ) )
	    {
	        $page = new BackupProBackupController();
	        $page->backup_files();
	    }
	}
	
	public function pluginLinks($links)
	{
	    $links['settings'] = sprintf( '<a href="%s"> %s </a>', admin_url( 'admin.php?page=backup_pro/settings' ), __( 'Settings', 'plugin_domain' ) );
	    return $links;
	}
	
	/**
	 * Action method to dispaly global error messages
	 */
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
	
	/**
	 * Wrapper to add success messages on settings save
	 */
	public function settingsNotices()
	{
	    $class =  $class = " updated ";
	    echo"<div class=\"$class\"> <p>".esc_html__($this->view_helper->m62Lang('settings_updated'));
	    echo "</p></div>";
	}


	/**
	 * Sets the BackupPro library for use
	 * @param BackupPro $context
	 */
	public function setContext(BackupPro $context)
	{
	    $this->context = $context;
	    return $this;
	}
}
