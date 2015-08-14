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
	 * Defines the Left Menu and internal view pages for the Admin
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
        add_submenu_page( null, 'Download Backup', null, 'manage_options', 'backup_pro/download', array($this, 'downloadBackup'));
        add_submenu_page( null, 'Confirm Remove Backup', null, 'manage_options', 'backup_pro/confirm_remove_backup', array($this, 'confirmRemoveBackup'));
        //add_submenu_page( 'backup_pro', 'Newd Storage', null, 'manage_options', 'backup_pro/new_storagge', array($this, 'settings'));
	}
	
	/**
	 * Now we're defining the processing scripts
	 * Wordpress, being an event based system, handles output all screwy
	 * so will send headers before our controller actions ccan work with them
	 * 
	 * This means that we can't redirect in our controllers so any action that
	 * required such has to have that logic in a proc* method
	 * 
	 * Oh, and even better, there also isn't any FlashMessenger mechanism so
	 * if we want to display messages, or notices/errors, we have to do some 
	 * fancy logic in query strings and checks
	 * 
	 * Also. Fuck me. WP also has a weird CSRF mechanism so we have to do 
	 * some logic there too
	 * 
	 * See procSettings() for a solid example
	 */
	
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
	
	public function procConfirmBackup()
	{
	    if( $_SERVER['REQUEST_METHOD'] == 'POST' && $this->getPost('page') == 'backup_pro/confirm_remove_backup' && check_admin_referer( 'remove_bp_backups' ) )
	    {
	        $delete_backups = $this->getPost('backups');
	        $type = $this->getPost('type');
	        if(!$delete_backups || count($delete_backups) == 0)
	        {
	            $section = ( $type == 'database' ? 'db_backups' : 'file_backups' );
                wp_redirect($this->url_base.'&section='.$section.'&remove_fail=yes');
                exit;
	        }
	        
	        print_r($_POST);
	        $page = new BackupProManageController($this);
	        $page->setBackupLib($this->context);
	        exit;
	    }
	    else
	    {
	        if( $this->getPost('remove_fail') == 'yes' && in_array($this->getPost('section'), array('db_backups', 'file_backups')) )
	        {
	            add_action( 'admin_notices', array( $this, 'backupRemoveErrorNotice' ), 30, array('settings_updated'));
	        }
	    }
	}
	
	public function procBackupRemove()
	{
	    
	}
	
	
	/**
	 * Action to process adding a new storage engine
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
	            case 'edit':
	               $page->editStorage();    
	            break;
	            
	            case 'new':
	                $page->newStorage();
                break;
	            default:
	                $page->viewStorage();
                break;
	        }
	    }
	    else 
	    {
	        $page = new BackupProSettingsController($this);
	        $page->setBackupLib($this->context)->settings();	        
	    }
	}
	
	public function confirmRemoveBackup()
	{
	    $page = new BackupProManageController($this);
	    $page->setBackupLib($this->context)->deleteBackupConfirm();	    
	}
	
	public function downloadBackup()
	{
	    if( $this->getPost('page') == 'backup_pro/download' && check_admin_referer( urlencode($this->getPost('id')) ) )
	    {
    	    $page = new BackupProManageController($this);
    	    $page->setBackupLib($this->context)->download();
    	    exit;
	    }
	}
	
	public function confirmBackupFiles()
	{
	    $page = new BackupProBackupController($this);
	    $page->setBackupLib($this->context)->backup('files');
	}
	
	public function confirmBackupDb()
	{
	    $page = new BackupProBackupController($this);
	    $page->setBackupLib($this->context)->backup();
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
	 * Wrapper to add success messages on settings save
	 */
	public function backupRemoveErrorNotice()
	{
	    $class =  $class = " error ";
	    echo"<div class=\"$class\"> <p>".esc_html__($this->view_helper->m62Lang('backups_not_found'));
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
