<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Controllers/Wordpress/Manage.php
 */

use mithra62\BackupPro\Platforms\Controllers\Wordpress AS WpController;
use mithra62\BackupPro\BackupPro AS BpInterface;

/**
 * Backup Pro - Wordpress Manage Backup Controller
 *
 * Contains the Manage Backup Controller Actions for Wordpress
 *
 * @package 	BackupPro\Wordpress\Controllers
 * @author		Eric Lamb <eric@mithra62.com>
 */
class BackupProManageController extends WpController implements BpInterface
{   
    /**
     * Download a backup action
     */
    public function download()
    {
        $encrypt = $this->services['encrypt'];
        $file_name = $encrypt->decode($this->getPost('id'));
        $type = $this->getPost('type');
        $storage = $this->services['backup']->setStoragePath($this->settings['working_directory']);
        if($type == 'files')
        {
            $file = $storage->getStorage()->getFileBackupNamePath($file_name);
        }
        else
        {
            $file = $storage->getStorage()->getDbBackupNamePath($file_name);
        }
    
        
        $backup_info = $this->services['backups']->setLocations($this->settings['storage_details'])->getBackupData($file);
        $download_file_path = false;
        if( !empty($backup_info['storage_locations']) && is_array($backup_info['storage_locations']) )
        {
            foreach($backup_info['storage_locations'] AS $storage_location)
            {
                if( $storage_location['obj']->canDownload() )
                {
                    $download_file_path = $storage_location['obj']->getFilePath($backup_info['file_name'], $backup_info['backup_type']); //next, get file path
                    break;
                }
            }
        }
    
        if($download_file_path && file_exists($download_file_path))
        {
            $this->services['files']->fileDownload($download_file_path);
            exit;
        }
    }
    
    /**
     * AJAX Action for updating a backup note
     */
    public function updateBackupNote()
    {
        $encrypt = $this->services['encrypt'];
        $file_name = $encrypt->decode($this->getPost('backup'));
        $backup_type = stripslashes_deep($this->getPost('backup_type'));
        $note_text = stripslashes_deep($this->getPost('note_text')); 
        if($note_text && $file_name)
        {
            $path = rtrim($this->settings['working_directory'], DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$backup_type;
            $this->services['backup']->getDetails()->addDetails($file_name, $path, array('note' => $note_text));
            echo json_encode(array('success'));
        }
        exit;
    }
    
    /**
     * Delete Backup Confirmation Action
     */
    public function deleteBackupConfirm()
    {
        $delete_backups = $this->getPost('backups');
        $type = $this->getPost('type');
        $backups = $this->validateBackups($delete_backups, $type);
        $variables = array(
            'settings' => $this->settings,
            'backups' => $backups,
            'backup_type' => $type,
            'method' => $this->getPost('method'),
            'errors' => $this->errors,
            'view_helper' => $this->view_helper,
            'url_base' => $this->url_base,
            'menu_data' => $this->backup_lib->getDashboardViewMenu(),
            'section' => 'db_backups',
            'theme_folder_url' => plugin_dir_url(self::name)
        );
    
        //$template = 'backuppro/delete_confirm';
    
        //ee()->view->cp_page_title = $this->services['lang']->__('dashboard');
        $template = 'admin/views/delete_confirm';
        $this->renderTemplate($template, $variables);
    }
    
    /**
     * Delete Backup Action
     */
    public function deleteBackups()
    {

    }
    
    /**
     * Validates the POST'd backup data and returns the clean array
     * @param array $delete_backups
     * @param string $type
     * @return multitype:array
     */
    public function validateBackups($delete_backups, $type)
    {   
        $encrypt = $this->services['encrypt'];
        $backups = array();
    
        $locations = $this->settings['storage_details'];
        $drivers = $this->services['backup']->getStorage()->getAvailableStorageDrivers();
        foreach($delete_backups AS $file_name)
        {
            $file_name = $encrypt->decode(urldecode($file_name));
            if( $file_name != '' )
            {
                $path = rtrim($this->settings['working_directory'], DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$type;
                $file_data = $this->services['backup']->getDetails()->getDetails($file_name, $path);
                $file_data = $this->services['backups']->getBackupStorageData($file_data, $locations, $drivers);
                $backups[] = $file_data;
            }
        }
    
        return $backups;
    }    
    
}