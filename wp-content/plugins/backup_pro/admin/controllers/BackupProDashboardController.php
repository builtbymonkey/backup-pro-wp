<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./backup_pro/admin/controllers/BackupProDashboardController.php
 */
 
use mithra62\BackupPro\Platforms\Controllers\Wordpress AS WpController;
use mithra62\BackupPro\BackupPro AS BpInterface;

/**
 * Backup Pro - Wordpress Dashboard Controller
 *
 * Contains the Dashboard Controller Actions for Wordpress
 *
 * @package 	BackupPro\Wordpress\Controllers
 * @author		Eric Lamb <eric@mithra62.com>
 */
class BackupProDashboardController extends WpController implements BpInterface
{   
    /**
     * The Dashboard view
     */
    public function index()
    {
        $backup = $this->services['backups'];
        $backups = $backup->setBackupPath($this->settings['working_directory'])->getAllBackups($this->settings['storage_details']);
        
        $backup_meta = $backup->getBackupMeta($backups);
        $available_space = $backup->getAvailableSpace($this->settings['auto_threshold'], $backups);
        
        $backups = $backups['database'] + $backups['files'];
        krsort($backups, SORT_NUMERIC);
        
        if(count($backups) > $this->settings['dashboard_recent_total'])
        {
            //we have to remove a few
            $filtered_backups = array();
            $count = 1;
            foreach($backups AS $time => $backup)
            {
                $filtered_backups[$time] = $backup;
                if($count >= $this->settings['dashboard_recent_total'])
                {
                    break;
                }
                $count++;
            }
            $backups = $filtered_backups;
        }
        
        $variables = array(
            'settings' => $this->settings,
            'backup_meta' => $backup_meta,
            'backups' => $backups,
            'available_space' => $available_space,
            'errors' => $this->errors,
            'view_helper' => $this->view_helper,
            'url_base' => $this->url_base,
            'menu_data' => $this->backup_lib->getDashboardViewMenu(),
            'section' => '',
            'theme_folder_url' => plugin_dir_url(self::name)
        );
        
        $template = 'admin/views/dashboard';
        $this->renderTemplate($template, $variables);
    }
    
    /**
     * The view database backups view
     */
    public function db_backups()
    {
        $backup = $this->services['backups'];
        $backups = $backup->setBackupPath($this->settings['working_directory'])->getAllBackups($this->settings['storage_details']);
        $backup_meta = $backup->getBackupMeta($backups);
        
        $variables = array(
            'settings' => $this->settings,
            'backup_meta' => $backup_meta,
            'backups' => $backups,
            'errors' => $this->errors,
            'view_helper' => $this->view_helper,
            'url_base' => $this->url_base,
            'menu_data' => $this->backup_lib->getDashboardViewMenu(),
            'section' => 'db_backups',
            'theme_folder_url' => plugin_dir_url(self::name)
        );
        
        $template = 'admin/views/database_backups';
        $this->renderTemplate($template, $variables);
    }
    
    /**
     * The view file backups view
     */
    public function file_backups()
    {
        $backup = $this->services['backups'];
        $backups = $backup->setBackupPath($this->settings['working_directory'])->getAllBackups($this->settings['storage_details']);
        $backup_meta = $backup->getBackupMeta($backups);
        
        $variables = array(
            'settings' => $this->settings,
            'backup_meta' => $backup_meta,
            'backups' => $backups,
            'errors' => $this->errors,
            'view_helper' => $this->view_helper,
            'url_base' => $this->url_base,
            'menu_data' => $this->backup_lib->getDashboardViewMenu(),
            'section' => 'file_backups',
            'theme_folder_url' => plugin_dir_url(self::name)
        );
        
        $template = 'admin/views/file_backups';
        $this->renderTemplate($template, $variables);
    }
    
}