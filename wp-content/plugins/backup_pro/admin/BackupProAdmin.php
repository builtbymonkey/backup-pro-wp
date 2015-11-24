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
	 * Below starts the initialization scripts and setup for the WP event system
	 * Note that none of this feels right to me...

	/**
	 * Sets the CSS file for the Admin 
	 */
	public function enqueueStyles() 
	{
	    if( strpos($this->getPost('page'), 'backup_pro') !== FALSE ) 
	    {
    		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/backup_pro_admin.css', array(), $this->version, 'all' );
    		wp_enqueue_style( $this->plugin_name.'_chosen', plugin_dir_url( __FILE__ ) . 'css/chosen.css', array(), $this->version, 'all' );
	    }
	}

	/**
	 * Loads up the JavaScript for the Admin
	 */
	public function enqueueScripts() 
	{
	    if( strpos($this->getPost('page'), 'backup_pro') !== FALSE )
	    {
    		wp_enqueue_script( 'bpchosen', plugin_dir_url( __FILE__ ) . 'js/chosen.jquery.js', array( 'jquery' ), $this->version, true );
    		wp_enqueue_script( 'bpdashboard', plugin_dir_url( __FILE__ ) . 'js/dashboard.js', array( 'jquery' ), $this->version, true );
    		wp_enqueue_script( 'bpbackup', plugin_dir_url( __FILE__ ) . 'js/backup.js', array( 'jquery' ), $this->version, true );
    		wp_enqueue_script( 'bpsettings', plugin_dir_url( __FILE__ ) . 'js/settings.js', array( 'jquery' ), $this->version, true );
    		wp_enqueue_script( 'wp_backup_pro', plugin_dir_url( __FILE__ ) . 'js/wp/backup_pro.js', array( 'jquery' ), $this->version, true );
    		wp_enqueue_script( 'bpglobal', plugin_dir_url( __FILE__ ) . 'js/global.js', array( 'jquery' ), $this->version, true );
    		
	    }
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
        add_submenu_page( null, 'Actually, for realsy, Remove the Backup', null, 'manage_options', 'backup_pro/remove_backup', array($this, 'procBackupRemove'));
        add_submenu_page( null, 'Restore Database', null, 'manage_options', 'backup_pro/restore_database', array($this, 'procDbRestore'));
        //add_submenu_page( 'backup_pro', 'Newd Storage', null, 'manage_options', 'backup_pro/new_storagge', array($this, 'settings'));
	}
	
	/**
	 * Now we're defining the processing scripts
	 * Wordpress, being an event based system, handles output all screwy
	 * so will send headers before our controller actions can work with them
	 * unless we do soemthing with a noheader=true query nonsense.
	 * 
	 * This means that we can't redirect in our controllers so any action that
	 * required such has to have that logic in a proc* method
	 * 
	 * Oh, and even better, there also isn't any FlashMessenger mechanism so
	 * if we want to display messages, or notices/errors, we have to do dumb 
	 * logic in query strings and checks
	 * 
	 * Also. Fuck me. WP also has a weird CSRF mechanism so we have to do 
	 * some logic there too
	 * 
	 * See procSettings() for a generic example
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
	    //check if we're processing the addition of a new storage location
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
	                wp_redirect($this->url_base.'settings&section='.$this->getPost('section').'&storage_added=yes');
	                exit;
	            }
	        }
	    }
	    else
	    {
	        //check if we're on the storage add action, and if we have to display  a success message, then do so
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
	    //here we process a storage edit location assuming we're good
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
            $data['location_id'] = $storage_id;
            $settings_errors = $this->services['backup']->getStorage()->validateDriver($this->services['validate'], $storage_details['storage_location_driver'], $data, $this->settings['storage_details']);
            if( !$settings_errors )
            {
                if( $this->services['backup']->getStorage()->getLocations()->setSetting($this->services['settings'])->update($storage_id, $data) )
                {
	                wp_redirect($this->url_base.'settings&section='.$this->getPost('section').'&storage_edited=yes');
	                exit;
                }
            }
	    }
	    else
	    {    
	        //on default edt, here, all we want to do is validate the ID is accurate if we have one
	        if( $this->getPost('page') == 'backup_pro/settings' && 
	            $this->getPost('section') == 'storage' && 
	            $this->getPost('id') != '' && 
	            $this->getPost('action') == 'edit' )
	        {
	            $storage_id = $this->getPost('id');
	            if( empty($this->settings['storage_details'][$storage_id]) )
	            {
                    wp_redirect($this->url_base.'settings&section=storage&edit_fail=yes');
                    exit;
	            }
	        }
	        
            if( $this->getPost('storage_edited') == 'yes' && $this->getPost('page') == 'backup_pro/settings' && $this->getPost('section') == 'storage' )
	        {
	            add_action( 'admin_notices', array( $this, 'storageEditNotice' ), 30, array('settings_updated'));
	        }
	    
	    }
	}
	
	public function procStorageRemove()
	{
	    if( $this->getPost('page') == 'backup_pro/settings' &&
	        $this->getPost('section') == 'storage' &&
	        $this->getPost('id') != '' &&
	        $this->getPost('action') == 'remove' )
	    {
	        if( count($this->settings['storage_details']) <= 1 )
	        {
	            add_action( 'admin_notices', array( $this, 'storageMinNeedFail' ), 30, array('settings_updated'));
	        }
	        
	        $storage_id = $this->getPost('id');
	        if( empty($this->settings['storage_details'][$storage_id]) )
	        {
                wp_redirect($this->url_base.'settings&section=storage&remove_fail=yes');
                exit;
	        }
	        
	        if( $_SERVER['REQUEST_METHOD'] == 'POST' && check_admin_referer( 'bpstorage-'.$storage_id) )
	        {
                $data = array();
                $data = array_map( 'stripslashes_deep', $_POST );
                $backups = $this->services['backups']->setBackupPath($this->settings['working_directory'])
                                                     ->getAllBackups($this->settings['storage_details'], $this->services['backup']->getStorage()->getAvailableStorageDrivers());
                
                if( $this->services['backup']->getStorage()->getLocations()->setSetting($this->services['settings'])->remove($storage_id, $data, $backups) )
                {
                    wp_redirect($this->url_base.'settings&section=storage&remove_success=yes');
                    exit;
                }                
	        }
	    }
	    
	    if( $this->getPost('remove_fail') == 'yes' )
	    {
            add_action( 'admin_notices', array( $this, 'storageRemoveFail' ), 30, array('settings_updated'));
	    }
	    
	    if( $this->getPost('remove_success') == 'yes' && $this->getPost('section') == 'storage' )
	    {
            add_action( 'admin_notices', array( $this, 'storageRemoveNotice' ), 30, array('settings_updated'));
	    }
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
	
	public function procDbRestore()
	{
        if( $_SERVER['REQUEST_METHOD'] == 'POST' && 
	        $this->getPost('page') == 'backup_pro/restore_database' && 
	        $this->getPost('id') != '' && 
	        check_admin_referer( 'restore_db_'.urlencode($this->getPost('id'))) )
	    {
            $encrypt = $this->services['encrypt'];
            $file_name = $encrypt->decode($this->getPost('id'));
            $storage = $this->services['backup']->setStoragePath($this->settings['working_directory']);
        
            $file = $storage->getStorage()->getDbBackupNamePath($file_name);
            $backup_info = $this->services['backups']->setLocations($this->settings['storage_details'])->getBackupData($file);
            $restore_file_path = false;
            foreach($backup_info['storage_locations'] AS $storage_location)
            {
                if( $storage_location['obj']->canRestore() )
                {
                    $restore_file_path = $storage_location['obj']->getFilePath($backup_info['file_name'], $backup_info['backup_type']); //next, get file path
                    break;
                }
            }
        
            if($restore_file_path && file_exists($restore_file_path))
            {
                $db_info = $this->platform->getDbCredentials();
                if( $this->services['restore']->setDbInfo($db_info)->setBackupInfo($backup_info)->database($db_info['database'], $restore_file_path, $this->settings, $this->services['shell']) )
                {
                    //ee()->session->set_flashdata('message_success', $this->services['lang']->__('database_restored'));
                    //ee()->functions->redirect($this->url_base.'db_backups');
                    wp_redirect($this->url_base.'&db_restore_complete=yes');
                    exit;                    
                }
            }
            else
            {
                //ee()->session->set_flashdata('message_error', $this->services['lang']->__('db_backup_not_found'));
                //ee()->functions->redirect($this->url_base.'db_backups');
                wp_redirect($this->url_base.'&db_restore_fail=yes');
                exit;                
            }
	    }
	    else 
	    {
	        if( $this->getPost('db_restore_complete') == 'yes' )
	        {
	            add_action( 'admin_notices', array( $this, 'dbRestoreCompleteNotice' ), 30, array('settings_updated'));
	        }
	        
	        if( $this->getPost('db_restore_fail') == 'yes' )
	        {
	            add_action( 'admin_notices', array( $this, 'dbRestoreFailNotice' ), 30, array('settings_updated'));
	        }	        
	    }
	}
	
	public function procIaMissedBackupEmail()
	{
	    $errors = $this->errors;
	    if( $this->settings['check_backup_state_cp_login'] == '1' && 
	        count($this->settings['backup_missed_schedule_notify_emails']) >= 1 &&
	        (isset($errors['db_backup_past_expectation']) || isset($errors['file_backup_past_expectation'])) 
	      )
	    {
	        $backups = $this->services['backups'];
	        $backup_data = $backups->setBackupPath($this->settings['working_directory'])->getAllBackups($this->settings['storage_details']);
	        $backup_meta = $backups->getBackupMeta($backup_data);
	        $notify = $this->services['notify']->setBackup( $this->services['backup']);
	        $this->services['backups']->getIntegrity()->notifyBackupState($backup_meta, $this->settings, $errors, $notify );
	    }
	}
	
	public function dashboard()
	{
	    $page = new BackupProDashboardController();
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
            
	        case 'restore':
	            $page = new BackupProRestoreController();
	            $page->setBackupLib($this->context)->restore_confirm();
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
	        $errors = $this->services['errors']->getErrors();
	        
	        if( $errors && count($errors) >= 1)
	        {	            
	            foreach($errors AS $error)
	            {
        	        $class = "error";
            	    echo"<div class=\"$class\"> <p>".esc_html__($this->view_helper->m62Lang($error));
            	    if( $error == 'no_storage_locations_setup' )
            	    {
            	        echo ' <a href="'.$this->url_base.'settings&section=storage&action=new&engine=local">'.$this->view_helper->m62Lang('setup_storage_location').'</a>';
            	    }
            	    elseif( $error == 'license_number' || $error == 'missing_license_number' )
            	    {
            	        echo ' <a href="'.$this->url_base.'settings&section=license">'.$this->view_helper->m62Lang('enter_license').'</a> or <a href="https://mithra62.com/projects/view/backup-pro">'.$this->view_helper->m62Lang('purchase_a_license').'</a>';
            	    }
            	    elseif( $error == 'invalid_working_directory' )
            	    {
            	        echo ' <a href="'.$this->url_base.'settings">'.$this->view_helper->m62Lang('check_working_dir').'</a>';
            	    }
            		elseif( $error == 'no_db_backups_exist_yet' )
            		{
            		    echo ' <a href="'.$this->url_base.'confirm_backup_db">'.$this->view_helper->m62Lang('would_you_like_to_backup_database_now').'</a>';
            		}
            		elseif( $error == 'no_file_backups_exist_yet' )
            		{
            		    echo ' <a href="'.$this->url_base.'confirm_backup_files">'.$this->view_helper->m62Lang('would_you_like_to_backup_files_now').'</a>';
            		}
            		elseif( $error == 'db_backup_past_expectation_stub' || $error == 'file_backup_past_expectation_stub' )
            		{
                        $backup = $this->services['backups'];
                        $backups = $backup->setBackupPath($this->settings['working_directory'])->getAllBackups($this->settings['storage_details']);
                        $backup_meta = $backup->getBackupMeta($backups);
                        
                        if( $error == 'db_backup_past_expectation_stub' )
                        {
                		    $lang = sprintf(
                		        $this->view_helper->m62Lang('db_backup_past_expectation'), 
                		        $this->view_helper->getRelativeDateTime($backup_meta['database']['newest_backup_taken_raw'], false), 
                		        $this->url_base.'confirm_backup_db'
                		    );
                        }
                        else if ( $error == 'file_backup_past_expectation_stub' )
                        {
                            $lang = sprintf(
                                $this->view_helper->m62Lang('files_backup_past_expectation'),
                                $this->view_helper->getRelativeDateTime($backup_meta['files']['newest_backup_taken_raw'], false),
                                $this->url_base.'confirm_backup_files'
                            );
                        }
            		    echo $lang;
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
	 * Wrapper to add the success message on successful storage edting
	 */
	public function storageEditNotice()
	{
	    $class =  $class = " updated ";
	    echo"<div class=\"$class\"> <p>".esc_html__($this->view_helper->m62Lang('storage_location_updated'));
	    echo "</p></div>";
	}

	/**
	 * Wrapper to add the success message on successful storage ad
	 */
	public function storageAddNotice()
	{
	    $class =  $class = " updated ";
	    echo"<div class=\"$class\"> <p>".esc_html__($this->view_helper->m62Lang('storage_location_added'));
	    echo "</p></div>";
	}
	
	public function storageMinNeedFail()
	{
	    $class =  $class = " error ";
	    echo"<div class=\"$class\"> <p>".esc_html__($this->view_helper->m62Lang('min_storage_location_needs'));
	    echo "</p></div>";
	}
	
	public function storageRemoveFail()
	{
	    $class =  $class = " error ";
	    echo"<div class=\"$class\"> <p>".esc_html__($this->view_helper->m62Lang('invalid_storage_id'));
	    echo "</p></div>";
	}
	
	public function storageRemoveNotice()
	{
	    $class =  $class = " updated ";
	    echo"<div class=\"$class\"> <p>".esc_html__($this->view_helper->m62Lang('storage_location_removed'));
	    echo "</p></div>";
	}
	
	public function dbRestoreCompleteNotice()
	{
	    $class =  $class = " updated ";
	    echo"<div class=\"$class\"> <p>".esc_html__($this->view_helper->m62Lang('database_restored'));
	    echo "</p></div>";
	}
	
	public function dbRestoreFailNotice()
	{
	    $class =  $class = " error ";
	    echo"<div class=\"$class\"> <p>".esc_html__($this->view_helper->m62Lang('database_restore_fail'));
	    echo "</p></div>";
	}
}
