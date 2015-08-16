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
		wp_enqueue_style( $this->plugin_name.'_chosen', plugin_dir_url( __FILE__ ) . 'css/chosen.css', array(), $this->version, 'all' );
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
        add_submenu_page( null, 'Confirm Remove Backup', null, 'manage_options', 'backup_pro/remove_backup', array($this, 'procBackupRemove'));
        //add_submenu_page( 'backup_pro', 'Newd Storage', null, 'manage_options', 'backup_pro/new_storagge', array($this, 'settings'));
	}
	
	/**
	 * Now we're defining the processing scripts
	 * Wordpress, being an event based system, handles output all screwy
	 * so will send headers before our controller actions can work with them
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
	 * Action to process the Settings form submission
	 * Note that this happens independantly from the 
	 * Settings Action 
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
            if( $this->getPost('updated') == 'yes' && $this->getPost('page') == 'backup_pro/settings' && $this->getPost('section') != 'storage' )
            {
                add_action( 'admin_notices', array( $this, 'settingsNotices' ), 30, array('settings_updated'));
            }
        }
	}
	
	/**
	 * Action to process adding a new storage engine
	 */
	public function procStorageAdd()
	{
	    if( $_SERVER['REQUEST_METHOD'] == 'POST' && 
	        $this->getPost('page') == 'backup_pro/settings' && 
	        $this->getPost('section') == 'storage' && 
	        $this->getPost('action') == 'new' &&
	        $this->getPost('engine') != '' &&
	        check_admin_referer( 'bpstorage' ) )
	    {
	        $data = array();
	        $data = array_map( 'stripslashes_deep', $_POST );
	
	        $engine = $this->getPost('engine', 'local');
	        $variables = array();
	        $variables['available_storage_engines'] = $this->services['backup']->getStorage()->getAvailableStorageDrivers();
	
	        if( !isset($variables['available_storage_engines'][$engine]) )
	        {
	            $engine = 'local';
	        }
	
	        $variables['form_data'] = $data;
	        $settings_errors = $this->services['backup']->getStorage()->validateDriver($this->services['validate'], $engine, $data, $this->settings['storage_details']);
	        if( !$settings_errors )
	        {
	            if( $this->services['backup']->getStorage()->getLocations()->setSetting($this->services['settings'])->create($engine, $variables['form_data']) )
	            {
	                ee()->session->set_flashdata('message_success', $this->services['lang']->__('storage_location_added'));
	                ee()->functions->redirect($this->url_base.'view_storage');
	                wp_redirect($this->url_base.'settings&section='.$this->getPost('section').'&storage_added=yes');
	                exit;
	            }
	        }
	    }
	    else
	    {
	        if( $this->getPost('storage_added') == 'yes' && $this->getPost('page') == 'backup_pro/settings' && $this->getPost('section') == 'storage' )
	        {
	            add_action( 'admin_notices', array( $this, 'storageAddNotice' ), 30, array('settings_updated'));
	        }
	    }
	}	
	
	/**
	 * Action to edit a storage location
	 * 
	 * We have to validate things on GET and POST, both, and
	 * ONLY process on POST
	 */
	public function procStorageEdit()
	{
	    if( $_SERVER['REQUEST_METHOD'] == 'POST' && 
	        $this->getPost('page') == 'backup_pro/settings' && 
	        $this->getPost('section') == 'storage' && 
	        $this->getPost('id') != '' && 
	        $this->getPost('action') == 'edit' && 
	        check_admin_referer( 'bpstorage-'.$this->getPost('id')) )
	    {
	        $storage_id = $this->getPost('id');
	        if( empty($this->settings['storage_details'][$storage_id]) )
	        {
	            wp_redirect($this->url_base.'settings&section=storage&edit_fail=yes');
	            exit;
	        }
	        
	        $storage_details = $this->settings['storage_details'][$storage_id];
	        
	        $data = array();
	        $data = array_map( 'stripslashes_deep', $_POST );
            $settings_errors = $this->services['backup']->getStorage()->validateDriver($this->services['validate'], $storage_details['storage_location_driver'], $data, $this->settings['storage_details']);
            if( !$settings_errors )
            {
                if( $this->services['backup']->getStorage()->getLocations()->setSetting($this->services['settings'])->update($storage_id, $data) )
                {
	                wp_redirect($this->url_base.'settings&section='.$this->getPost('section').'&storage_edited=yes');
	                exit;
                    ee()->session->set_flashdata('message_success', $this->services['lang']->__('storage_location_updated'));
                    ee()->functions->redirect($this->url_base.'view_storage');
                }
            }
	    }
	    else
	    {    
	        if( $this->getPost('page') == 'backup_pro/settings' && 
	            $this->getPost('section') == 'storage' && 
	            $this->getPost('id') != '' && 
	            $this->getPost('action') == 'edit' )
	        {
	            $storage_id = $this->getPost('id');
	            if( empty($this->settings['storage_details'][$storage_id]) )
	            {
	                
	                //ee()->session->set_flashdata('message_error', $this->services['lang']->__('invalid_storage_id'));
	                //ee()->functions->redirect($this->url_base.'view_storage');
                    wp_redirect($this->url_base.'settings&section=storage&edit_fail=yes');
                    exit;
	            }
	        }
	    
	    }
	}
	
	public function procStorageRemove()
	{
	    //wp_redirect('/');
	}
	
	/**
	 * Backup Remove Confirmation Action 
	 * 
	 * All we're really doing here is validating the POST data to ensure the backups are
	 * 1. Being passed correctly
	 * 2. Actually real backups
	 * 
	 * If they're not, we're just bouncing out and setting a flag to set the error message
	 */
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
	        
	        $page = new BackupProManageController($this);
	        $page->setBackupLib($this->context);
	        $delete_backups = $page->validateBackups($delete_backups, $type);
	        if(!$delete_backups || count($delete_backups) == 0)
	        {
	            $section = ( $type == 'database' ? 'db_backups' : 'file_backups' );
	            wp_redirect($this->url_base.'&section='.$section.'&remove_fail=yes');
	            exit;
	        }
	    }
	    else
	    {
	        //the confirm remove page should only be accessible via POST so bounce out if we're not even doing that
	        if($this->getPost('page') == 'backup_pro/confirm_remove_backup' && $_SERVER['REQUEST_METHOD'] != 'POST' )
	        {
	            wp_redirect($this->url_base);
	            exit;
	        }
	        
	        if( $this->getPost('remove_fail') == 'yes' && in_array($this->getPost('section'), array('db_backups', 'file_backups')) )
	        {
	            add_action( 'admin_notices', array( $this, 'backupRemoveErrorNotice' ), 30, array('settings_updated'));
	        }
	    }
	}
	
	/**
	 * Backup Remove Processing Action
	 */
	public function procBackupRemove()
	{
	    if( $_SERVER['REQUEST_METHOD'] == 'POST' && $this->getPost('page') == 'backup_pro/remove_backup' && check_admin_referer( 'really_for_reals_remove_bp_backups' ) )
	    {
	        $delete_backups = $this->getPost('backups');
	        $type = $this->getPost('type');
	        $page = new BackupProManageController($this);
	        $page->setBackupLib($this->context);
	        $backups = $page->validateBackups($delete_backups, $type);
	        
	        if( $this->services['backups']->setBackupPath($this->settings['working_directory'])->removeBackups($backups) )
	        {
	            $section = ( $type == 'database' ? 'db_backups' : 'file_backups' );
	            wp_redirect($this->url_base.'&section='.$section.'&remove_success=yes');
	            exit;	            
	        }
	        else
	        {
	            $section = ( $type == 'database' ? 'db_backups' : 'file_backups' );
	            wp_redirect($this->url_base.'&section='.$section.'&remove_fail=yes');
	            exit;
	        }	        
	    }
	    else
	    {
	        if( $this->getPost('remove_success') == 'yes' && in_array($this->getPost('section'), array('db_backups', 'file_backups')) )
	        {
	            add_action( 'admin_notices', array( $this, 'backupRemoveSuccessNotice' ), 30, array('settings_updated'));
	        }	        
	    }
	}

	/**
	 * Action to take a backup
	 * 
	 * This should NEVER be called outside of a secure POST along with the nonce
	 */
	public function procBackupDatabase()
	{
	    if( $_SERVER['REQUEST_METHOD'] == 'POST' && $this->getPost('page') == 'backup_pro/backup_database' && $this->getPost('go_db') == 'ok' && check_admin_referer( 'backup_db' ) )
	    {
	        $page = new BackupProBackupController();
	        if( $page->backup_database() )
	        {
	            wp_redirect($this->url_base.'&section=db_backups&backup_complete=yes');
	            exit;
	        }
	        else
	        {
	            echo "Something went terriby wrong and your backup coudn't complete...";
	            exit;
	        }
	        
	        exit;
	    }
	    else
	    {
	        if( $this->getPost('page') == 'backup_pro/' && $this->getPost('section') == 'db_backups' && $this->getPost('backup_complete') == 'yes' )
	        {
	            add_action( 'admin_notices', array( $this, 'backupCompleteNotice' ), 30, array('settings_updated'));
	        }
	    }
	}
	
	public function procBackupFiles()
	{
	    if( $_SERVER['REQUEST_METHOD'] == 'POST' && $this->getPost('page') == 'backup_pro/backup_files' && $this->getPost('go_files') == 'ok' && check_admin_referer( 'backup_files' ) )
	    {
	        $page = new BackupProBackupController();
	        if( $page->backup_files() )
	        {
	            wp_redirect($this->url_base.'&section=file_backups&backup_complete=yes');
	            exit;	            
	        }
	    }
	    else
	    {
	        if( $this->getPost('page') == 'backup_pro/' && $this->getPost('section') == 'file_backups' && $this->getPost('backup_complete') == 'yes' )
	        {
	            add_action( 'admin_notices', array( $this, 'backupCompleteNotice' ), 30, array('settings_updated'));
	        }
	    }	    
	}	
	
	public function procBackupNote()
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
	            
	            case 'remove':
	               $page->removeStorage();    
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
	 * Wrapper to add error message on backup remove
	 */
	public function backupRemoveErrorNotice()
	{
	    $class =  $class = " error ";
	    echo"<div class=\"$class\"> <p>".esc_html__($this->view_helper->m62Lang('backups_not_found'));
	    echo "</p></div>";
	}

	/**
	 * Wrapper to add success messages on settings save
	 */
	public function backupRemoveSuccessNotice()
	{
	    $class =  $class = " updated ";
	    echo"<div class=\"$class\"> <p>".esc_html__($this->view_helper->m62Lang('backups_deleted'));
	    echo "</p></div>";
	}
	
	/**
	 * Wrapper to add the success message on backup complete
	 */
	public function backupCompleteNotice()
	{
	    $class =  $class = " updated ";
	    echo"<div class=\"$class\"> <p>".esc_html__($this->view_helper->m62Lang('backup_progress_bar_stop'));
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
