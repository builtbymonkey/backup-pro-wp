<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./backup_pro/admin/controllers/BackupProRestoreController.php
 */
 
use mithra62\BackupPro\Platforms\Controllers\Wordpress AS WpController;
use mithra62\BackupPro\BackupPro AS BpInterface;

/**
 * Backup Pro - Wordpress Restore Controller
 *
 * Contains the Restore Controller Actions for Wordpress
 *
 * @package 	BackupPro\Wordpress\Controllers
 * @author		Eric Lamb <eric@mithra62.com>
 */
class BackupProRestoreController extends WpController implements BpInterface
{ 
    /**
     * Restore database confirm
     */
    public function restore_confirm()
    {
        $encrypt = $this->services['encrypt'];
        $file_name = $encrypt->decode($this->getPost('id'));
        $storage = $this->services['backup']->setStoragePath($this->settings['working_directory']);
    
        $file = $storage->getStorage()->getDbBackupNamePath($file_name);
        $backup_info = $this->services['backups']->setLocations($this->settings['storage_details'])->getBackupData($file);
        $variables = array(
            'settings' => $this->settings,
            'backup' => $backup_info,
            'errors' => $this->errors,
            'method' => $this->getPost('method'),
            'view_helper' => $this->view_helper,
            'url_base' => $this->url_base,
            'menu_data' => $this->backup_lib->getDashboardViewMenu(),
            'section' => 'db_backups',
            'theme_folder_url' => plugin_dir_url(self::name)
        );
        
        $template = 'admin/views/restore_confirm';
        $this->renderTemplate($template, $variables);
    }
    
    /**
     * Restore database action
     */
    public function restore_database()
    {

    }
}